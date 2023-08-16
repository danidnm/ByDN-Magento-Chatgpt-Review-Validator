<?php

namespace DanielNavarro\ChatGptReviewValidator\Model;

use DanielNavarro\ChatGptReviewValidator\Api\Data\ReviewInterface;
use Magento\Framework\Model\AbstractExtensibleModel;

class Review extends AbstractExtensibleModel implements ReviewInterface
{
    /**
     * Assigns resource model for the entity
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\DanielNavarro\ChatGptReviewValidator\Model\ResourceModel\Review::class);
        $this->setIdFieldName('id');
    }

    /**
     * Returns entity ID (this ID is not the review ID but the ID of the extra info table)
     *
     * @return null|int
     */
    public function getId(): ?int
    {
        return $this->getData(self::ID);
    }

    /**
     * Returns review id for this entity
     *
     * @return null|int
     */
    public function getGptReviewId(): ?int
    {
        return $this->getData(self::GPT_REVIEW_ID);
    }

    /**
     * Returns OpenAI validation status
     *
     * @return string
     */
    public function getGptStatus(): string
    {
        return $this->getData(self::GPT_STATUS);
    }

    /**
     * Returns the date when the review was moderated
     *
     * @return string|null
     */
    public function getGptValidatedAt(): string|null
    {
        return $this->getData(self::GPT_VALIDATED_AT);
    }

    /**
     * Return the OpenAI validation result
     *
     * @return string
     */
    public function getGptResult(): string
    {
        return $this->getData(self::GPT_RESULT);
    }

    /**
     * Returns OpenAI problems found
     *
     * @return string|null
     */
    public function getGptProblems(): string|null
    {
        return $this->getData(self::GPT_PROBLEMS);
    }

    /**
     * Returns scores for each category
     *
     * @return string|null
     */
    public function getGptScoreSummary(): string|null
    {
        return $this->getData(self::GPT_SCORE_SUMMARY);
    }

    /**
     * Returns if this review is excluded for training
     *
     * @return int|null
     */
    public function getGptExcludedForTraining(): int
    {
        return $this->getData(self::GPT_EXCLUDED_FOR_TRAINING);
    }

    /**
     * Sets the review ID
     *
     * @param int $reviewId
     * @return ReviewInterface
     */
    public function setGptReviewId(int $reviewId): ReviewInterface
    {
        return $this->setData(self::GPT_REVIEW_ID, $reviewId);
    }

    /**
     * Set review OpenAI moderation status
     *
     * @param string $status
     * @return ReviewInterface
     */
    public function setGptStatus(string $status): ReviewInterface
    {
        return $this->setData(self::GPT_STATUS, $status);
    }

    /**
     * Sets the OpenAI moderation date for this review
     *
     * @param mixed $validatedAt
     * @return ReviewInterface
     */
    public function setGptValidatedAt(mixed $validatedAt): ReviewInterface
    {
        return $this->setData(self::GPT_VALIDATED_AT, $validatedAt);
    }

    /**
     * Set OpenAI moderation result for the review
     *
     * @param string $result
     * @return ReviewInterface
     */
    public function setGptResult(string $result): ReviewInterface
    {
        return $this->setData(self::GPT_RESULT, $result);
    }

    /**
     * Set moderation problems found for the review
     *
     * @param mixed $problems
     * @return ReviewInterface
     */
    public function setGptProblems(mixed $problems): ReviewInterface
    {
        return $this->setData(self::GPT_PROBLEMS, $problems);
    }

    /**
     * Sets moderation scores for the review (should be a json)
     *
     * @param mixed $scoreSummary
     * @return ReviewInterface
     */
    public function setGptScoreSummary(mixed $scoreSummary): ReviewInterface
    {
        return $this->setData(self::GPT_SCORE_SUMMARY, $scoreSummary);
    }

    /**
     * Sets excluded for training flag for the review
     *
     * @param int $excludedForTraining
     * @return ReviewInterface
     */
    public function setGptExcludedForTraining(int $excludedForTraining): ReviewInterface
    {
        return $this->setData(self::GPT_EXCLUDED_FOR_TRAINING, $excludedForTraining);
    }
}
