<?php

namespace DanielNavarro\ChatGptReviewValidator\Model;

class Validator
{
    public const RESULT_FLAGGED_NO = 0;
    public const RESULT_FLAGGED_YES = 1;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
    private $timezone;
    /**
     * @var \DanielNavarro\ChatGpt\Model\ChatGpt\Moderation
     */
    private $chatGptModeration;

    /**
     * @var \DanielNavarro\ChatGptReviewValidator\Helper\Config
     */
    private $chatGptReviewValidationConfig;

    /**
     * @var \DanielNavarro\Logger\Model\LoggerInterface
     */
    private $logger;

    /**
     * @param \DanielNavarro\ChatGpt\Model\ChatGpt\Moderation $chatGptModeration
     * @param \DanielNavarro\ChatGptReviewValidator\Helper\Config $chatGptReviewValidationConfig
     */
    public function __construct(
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone,
        \DanielNavarro\ChatGpt\Model\ChatGpt\Moderation $chatGptModeration,
        \DanielNavarro\ChatGptReviewValidator\Helper\Config $chatGptReviewValidationConfig,
        \DanielNavarro\Logger\Model\LoggerInterface $logger
    ) {
        $this->timezone = $timezone;
        $this->chatGptModeration = $chatGptModeration;
        $this->chatGptReviewValidationConfig = $chatGptReviewValidationConfig;
        $this->logger = $logger;
    }

    /**
     * @param \Magento\Review\Model\Review $review
     * @return array
     */
    public function validateReview(\Magento\Review\Model\Review $review) {

        // Check if it is enabled
        if (!$this->chatGptReviewValidationConfig->isEnabled()) {
            return [false, $review];
        }

        // Log info
        $this->logger->writeInfo(__METHOD__, __LINE__, 'Validating review ' . $review->getId());

        // Extract review info to be validated
        $fullText = $this->getInfoForValidation($review);

        // Validate with moderation model
        $result = $this->chatGptModeration->moderateText($fullText);

//        // Log results
//        $this->logger->writeInfo(__METHOD__, __LINE__, 'Results:');
//        $this->logger->writeInfo(__METHOD__, __LINE__, $result);

        // If not ok, return the review as it is
        if (empty($result)) {
            return [false, $review];
        }

        // Process scores
        $result = $this->processResultScores($result);

//        // Log results
//        $this->logger->writeInfo(__METHOD__, __LINE__, 'Processed results:');
//        $this->logger->writeInfo(__METHOD__, __LINE__, $result);

        // Extract problems
        $problems = $this->extractProblems($result);

//        // Log results
//        $this->logger->writeInfo(__METHOD__, __LINE__, 'Problems found:');
//        $this->logger->writeInfo(__METHOD__, __LINE__, $result);

        // Update review with the GPT processed info
        $review = $this->updateReview($review, $result, $problems);

        // Return result
        return [true, $review];
    }

    /**
     * @param $review
     * @return string
     */
    private function getInfoForValidation($review) {
        return $review->getNickname() . ' / ' . $review->getTitle() . ' / ' .  $review->getDetail();
    }

    /**
     * @param $result
     * @return array
     */
    private function processResultScores($result) {

        // Allowed máximum scores
        $maxScores = $this->chatGptReviewValidationConfig->getMaximumScores();

        // Iterate over results and set review data
        foreach ($result['categories'] as $category => $score) {

            // Get maximum score
            $maxScore = ($maxScores[$category] / 100) ?? 0.25;

            // Processed data for the category
            $newData = [
                'score' => $score,
                'maxScore' => $maxScore,
                'flag' => ($score > $maxScore) ? '1' : '0'
            ];

            // Set new data
            $result['categories'][$category] = $newData;
        }

        return $result;
    }

    /**
     * @param $result
     * @return array
     */
    private function extractProblems($result) {

        // List of problems found
        $problems = [];

        // Iterate and extract flagged categories
        foreach ($result['categories'] as $category => $data) {
            if ($data['flag'] == '1') {
                $problems[] = $category;
            }
        }

        return $problems;
    }

    /**
     * @param $review
     * @param $problems
     * @return mixed
     */
    private function updateReview($review, $result, $problems) {

        // Set new status and date
        $review->setGptStatus(\DanielNavarro\ChatGptReviewValidator\Model\Source\Review\Status::REVIEW_STATUS_PROCESSED);
        $review->setGptValidatedAt($this->timezone->date()->format('Y-m-d H:i:s'));
        $review->setGptExcludedForTraining(0);

        // Iterate over results and set review data
        if (!empty($problems)) {
            $review->setGptResult(self::RESULT_FLAGGED_YES);
            $review->setGptProblems(implode(',', $problems));
        }
        else {
            $review->setGptResult(self::RESULT_FLAGGED_NO);
            $review->setGptProblems('');
        }

        // Add detailed data
        $result = \json_encode($result);
        $review->setGptScoreSummary($result);

        // Modify review status if needed
        if ($this->chatGptReviewValidationConfig->isAutoValidationEnabled()) {
            if ($review->getGptResult() == self::RESULT_FLAGGED_YES) {
                $review->setStatusId(\Magento\Review\Model\Review::STATUS_NOT_APPROVED);
            }
            else {
                $review->setStatusId(\Magento\Review\Model\Review::STATUS_APPROVED);
            }
        }

        return $review;
    }
}
