<?php

namespace Bydn\ChatGptReviewValidator\Model;

class Validator
{
    /**
     * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
    private $timezone;
    /**
     * @var \Bydn\ChatGpt\Model\ChatGpt\Moderation
     */
    private $chatGptModeration;

    /**
     * @var \Bydn\ChatGptReviewValidator\Helper\Config
     */
    private $chatGptReviewValidationConfig;

    /**
     * @param \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone
     * @param \Bydn\ChatGpt\Model\ChatGpt\Moderation $chatGptModeration
     * @param \Bydn\ChatGptReviewValidator\Helper\Config $chatGptReviewValidationConfig
     */
    public function __construct(
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone,
        \Bydn\ChatGpt\Model\ChatGpt\Moderation $chatGptModeration,
        \Bydn\ChatGptReviewValidator\Helper\Config $chatGptReviewValidationConfig
    ) {
        $this->timezone = $timezone;
        $this->chatGptModeration = $chatGptModeration;
        $this->chatGptReviewValidationConfig = $chatGptReviewValidationConfig;
    }

    /**
     * Performs a review moderation against OpenAI moderation service
     *
     * @param \Magento\Review\Model\Review $review
     * @return array
     */
    public function validateReview(\Magento\Review\Model\Review $review)
    {
        // Check if it is enabled
        if (!$this->chatGptReviewValidationConfig->isEnabled()) {
            return [false, $review];
        }

        // Extract review info to be validated
        $fullText = $this->getInfoForValidation($review);

        // Validate with moderation model
        $result = $this->chatGptModeration->moderateText($fullText);

        // If not ok, return the review as it is
        if (empty($result)) {
            return [false, $review];
        }

        // Process scores
        $result = $this->processResultScores($result);

        // Extract problems
        $problems = $this->extractProblems($result);

        // Update review with the GPT processed info
        $review = $this->updateReview($review, $result, $problems);

        // Return result
        return [true, $review];
    }

    /**
     * Returns a string with all the text to be moderated
     *
     * @param \Magento\Review\Model\Review $review
     * @return string
     */
    private function getInfoForValidation(\Magento\Review\Model\Review $review)
    {
        return $review->getNickname() . ' / ' . $review->getTitle() . ' / ' .  $review->getDetail();
    }

    /**
     * Process scores from OpenAI moderation service comparing them to the threshold of each category
     *
     * @param array $result
     * @return array
     */
    private function processResultScores(array $result)
    {
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
     * Extract problems found from scores
     *
     * @param array $result
     * @return array
     */
    private function extractProblems(array $result)
    {
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
     * Updates a review with the new info and changes its status if configured
     *
     * @param \Magento\Review\Model\Review $review
     * @param array $result
     * @param array $problems
     * @return mixed
     */
    private function updateReview(\Magento\Review\Model\Review $review, array $result, array $problems)
    {
        // Set new status and date
        $review->setGptStatus(
            \Bydn\ChatGptReviewValidator\Model\Source\Review\Status::REVIEW_STATUS_PROCESSED
        );
        $review->setGptValidatedAt($this->timezone->date()->format('Y-m-d H:i:s'));
        $review->setGptExcludedForTraining(0);

        // Iterate over results and set review data
        if (!empty($problems)) {
            $review->setGptResult(
                \Bydn\ChatGptReviewValidator\Model\Source\Review\Result::REVIEW_RESULT_FLAGGED
            );
            $review->setGptProblems(implode(',', $problems));
        } else {
            $review->setGptResult(\Bydn\ChatGptReviewValidator\Model\Source\Review\Result::REVIEW_RESULT_OK);
            $review->setGptProblems('');
        }

        // Add detailed data
        $result = \json_encode($result);
        $review->setGptScoreSummary($result);

        // Modify review status if needed
        if ($this->chatGptReviewValidationConfig->isAutoValidationEnabled()) {
            if ($review->getGptResult() ==
                \Bydn\ChatGptReviewValidator\Model\Source\Review\Result::REVIEW_RESULT_FLAGGED) {
                $review->setStatusId(\Magento\Review\Model\Review::STATUS_NOT_APPROVED);
            } else {
                $review->setStatusId(\Magento\Review\Model\Review::STATUS_APPROVED);
            }
        }

        return $review;
    }
}
