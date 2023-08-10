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
     * @param \DanielNavarro\ChatGpt\Model\ChatGpt\Moderation $chatGptModeration
     * @param \DanielNavarro\ChatGptReviewValidator\Helper\Config $chatGptReviewValidationConfig
     */
    public function __construct(
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone,
        \DanielNavarro\ChatGpt\Model\ChatGpt\Moderation $chatGptModeration,
        \DanielNavarro\ChatGptReviewValidator\Helper\Config $chatGptReviewValidationConfig
    ) {
        $this->timezone = $timezone;
        $this->chatGptModeration = $chatGptModeration;
        $this->chatGptReviewValidationConfig = $chatGptReviewValidationConfig;
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

        // Extract review info to be validated
        $fullText = $review->getNickname() . ' ' . $review->getTitle() . $review->getDetail();

        // Validate with moderation model
        $result = $this->chatGptModeration->moderateText($fullText);

        // If not ok, return the review as it is
        if (empty($result)) {
            return [false, $review];
        }

        // Set new status and date
        $review->setGptReviewId($review->getId());
        $review->setGptStatus(\DanielNavarro\ChatGptReviewValidator\Model\Source\Review\Status::REVIEW_STATUS_PROCESSED);
        $review->setGptValidatedAt($this->timezone->date()->format('Y-m-d H:i:s'));
        $review->setGptExcludedForTraining(0);

        // Clean results with default data
        $review->setGptResult(self::RESULT_FLAGGED_NO);
        $review->setGptProblems('');

        // Iterate over results and set review data
        $problems = [];
        foreach ($result as $category => $score) {
            if ($score > 0.2) {
                $problems[] = $category;
                $review->setGptResult(self::RESULT_FLAGGED_YES);
            }
        }

        // Modify review status if needed
        if ($this->chatGptReviewValidationConfig->isAutoValidationEnabled()) {
            if ($review->getGptResult() == self::RESULT_FLAGGED_YES) {
                $review->setStatusId(\Magento\Review\Model\Review::STATUS_NOT_APPROVED);
            }
            else {
                $review->setStatusId(\Magento\Review\Model\Review::STATUS_APPROVED);
            }
        }

        // Set all problems as string
        $problems = implode(',', $problems);
        $review->setGptProblems($problems);

        // Return result
        return [true, $review];
    }
}
