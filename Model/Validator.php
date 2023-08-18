<?php

namespace Bydn\OpenAiReviewValidator\Model;

class Validator
{
    /**
     * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
    private $timezone;
    /**
     * @var \Bydn\OpenAi\Model\OpenAi\Moderation
     */
    private $openAiModeration;

    /**
     * @var \Bydn\OpenAi\Model\OpenAi\Completions
     */
    private $openAiCompletions;

    /**
     * @var \Bydn\OpenAiReviewValidator\Helper\Config
     */
    private $openAiReviewValidationConfig;

    /**
     * @param \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone
     * @param \Bydn\OpenAi\Model\OpenAi\Moderation $openAiModeration
     * @param \Bydn\OpenAiReviewValidator\Helper\Config $openAiReviewValidationConfig
     */
    public function __construct(
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone,
        \Bydn\OpenAi\Model\OpenAi\Moderation $openAiModeration,
        \Bydn\OpenAi\Model\OpenAi\Completions $openAiCompletions,
        \Bydn\OpenAiReviewValidator\Helper\Config $openAiReviewValidationConfig
    ) {
        $this->timezone = $timezone;
        $this->openAiModeration = $openAiModeration;
        $this->openAiCompletions = $openAiCompletions;
        $this->openAiReviewValidationConfig = $openAiReviewValidationConfig;
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
        if (!$this->openAiReviewValidationConfig->isEnabled()) {
            return [false, $review];
        }

        // Validate language if enabled
        $languageResult = [];
        if ($this->openAiReviewValidationConfig->isLanguageValidationEnabled()) {
            $languageResult = $this->validateLanguage($review);
            if (empty($languageResult)) {
                return [false, $review];
            }

        }

        // Validate spam if enabled
        $spamResult = [];
        if ($this->openAiReviewValidationConfig->isSpamValidationEnabled()) {
            $spamResult = $this->validateSpam($review);
            if (empty($spamResult)) {
                return [false, $review];
            }

        }

        // Validate unrelated if enabled
        $unrelatedResult = [];
        if ($this->openAiReviewValidationConfig->isUnrelatedValidationEnabled()) {
            $unrelatedResult = $this->validateUnrelated($review);
            if (empty($unrelatedResult)) {
                return [false, $review];
            }

        }

        // Should be any results
        if (empty($languageResult) && empty($spamResult) && empty($unrelatedResult)) {
            return [false, $review];
        }

        // Merge all the results
        $result = array_merge_recursive($languageResult, $spamResult, $unrelatedResult);

        // Process scores
        $result = $this->processResultScores($result);

        // Extract problems
        $problems = $this->extractProblems($result);

        // Update review with the OPENAI processed info
        $review = $this->updateReview($review, $result, $problems);

        // Return result
        return [true, $review];
    }

    /**
     * Calls OpenAI API to look for abusive language
     *
     * @param \Magento\Review\Model\Review $review
     * @return array|mixed
     */
    private function validateLanguage(\Magento\Review\Model\Review $review) {

        // Extract review info to be validated
        $fullText = $this->getInfoForValidation($review);

        // Validate with moderation model
        return $this->openAiModeration->moderateText($fullText);
    }

    /**
     * Calls OpenAI API to look for spam language
     *
     * @param \Magento\Review\Model\Review $review
     * @return array|mixed
     */
    private function validateSpam(\Magento\Review\Model\Review $review) {
        $result = [];
        $result['categories']['spam'] = 0.1;
        return $result;
    }



    /**
     * Calls OpenAI API to look for unrelated language
     *
     * @param \Magento\Review\Model\Review $review
     * @return array|mixed
     */
    private function validateUnrelated(\Magento\Review\Model\Review $review) {
        $result = [];
        $result['categories']['unrelated'] = 0.5;
        return $result;
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
        $maxScores = $this->openAiReviewValidationConfig->getMaximumScores();

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
        $review->setOpenAiStatus(
            \Bydn\OpenAiReviewValidator\Model\Source\Review\Status::REVIEW_STATUS_PROCESSED
        );
        $review->setOpenAiValidatedAt($this->timezone->date()->format('Y-m-d H:i:s'));
        $review->setOpenAiExcludedForTraining(0);

        // Iterate over results and set review data
        if (!empty($problems)) {
            $review->setOpenAiResult(
                \Bydn\OpenAiReviewValidator\Model\Source\Review\Result::REVIEW_RESULT_FLAGGED
            );
            $review->setOpenAiProblems(implode(',', $problems));
        } else {
            $review->setOpenAiResult(\Bydn\OpenAiReviewValidator\Model\Source\Review\Result::REVIEW_RESULT_OK);
            $review->setOpenAiProblems('');
        }

        // Add detailed data
        $result = \json_encode($result);
        $review->setOpenAiScoreSummary($result);

        // Modify review status if needed
        if ($this->openAiReviewValidationConfig->isAutoValidationEnabled()) {
            if ($review->getOpenAiResult() ==
                \Bydn\OpenAiReviewValidator\Model\Source\Review\Result::REVIEW_RESULT_FLAGGED) {
                $review->setStatusId(\Magento\Review\Model\Review::STATUS_NOT_APPROVED);
            } else {
                $review->setStatusId(\Magento\Review\Model\Review::STATUS_APPROVED);
            }
        }

        return $review;
    }
}
