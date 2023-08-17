<?php

namespace Bydn\OpenAiReviewValidator\Model;

use Bydn\OpenAiReviewValidator\Api\Data\ReviewInterface;
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
        $this->_init(\Bydn\OpenAiReviewValidator\Model\ResourceModel\Review::class);
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
    public function getOpenAiReviewId(): ?int
    {
        return $this->getData(self::OPENAI_REVIEW_ID);
    }

    /**
     * Returns OpenAI validation status
     *
     * @return string
     */
    public function getOpenAiStatus(): string
    {
        return $this->getData(self::OPENAI_STATUS);
    }

    /**
     * Returns the date when the review was moderated
     *
     * @return string|null
     */
    public function getOpenAiValidatedAt(): string|null
    {
        return $this->getData(self::OPENAI_VALIDATED_AT);
    }

    /**
     * Return the OpenAI validation result
     *
     * @return string
     */
    public function getOpenAiResult(): string
    {
        return $this->getData(self::OPENAI_RESULT);
    }

    /**
     * Returns OpenAI problems found
     *
     * @return string|null
     */
    public function getOpenAiProblems(): string|null
    {
        return $this->getData(self::OPENAI_PROBLEMS);
    }

    /**
     * Returns scores for each category
     *
     * @return string|null
     */
    public function getOpenAiScoreSummary(): string|null
    {
        return $this->getData(self::OPENAI_SCORE_SUMMARY);
    }

    /**
     * Returns if this review is excluded for training
     *
     * @return int|null
     */
    public function getOpenAiExcludedForTraining(): int
    {
        return $this->getData(self::OPENAI_EXCLUDED_FOR_TRAINING);
    }

    /**
     * Sets the review ID
     *
     * @param int $reviewId
     * @return ReviewInterface
     */
    public function setOpenAiReviewId(int $reviewId): ReviewInterface
    {
        return $this->setData(self::OPENAI_REVIEW_ID, $reviewId);
    }

    /**
     * Set review OpenAI moderation status
     *
     * @param string $status
     * @return ReviewInterface
     */
    public function setOpenAiStatus(string $status): ReviewInterface
    {
        return $this->setData(self::OPENAI_STATUS, $status);
    }

    /**
     * Sets the OpenAI moderation date for this review
     *
     * @param mixed $validatedAt
     * @return ReviewInterface
     */
    public function setOpenAiValidatedAt(mixed $validatedAt): ReviewInterface
    {
        return $this->setData(self::OPENAI_VALIDATED_AT, $validatedAt);
    }

    /**
     * Set OpenAI moderation result for the review
     *
     * @param string $result
     * @return ReviewInterface
     */
    public function setOpenAiResult(string $result): ReviewInterface
    {
        return $this->setData(self::OPENAI_RESULT, $result);
    }

    /**
     * Set moderation problems found for the review
     *
     * @param mixed $problems
     * @return ReviewInterface
     */
    public function setOpenAiProblems(mixed $problems): ReviewInterface
    {
        return $this->setData(self::OPENAI_PROBLEMS, $problems);
    }

    /**
     * Sets moderation scores for the review (should be a json)
     *
     * @param mixed $scoreSummary
     * @return ReviewInterface
     */
    public function setOpenAiScoreSummary(mixed $scoreSummary): ReviewInterface
    {
        return $this->setData(self::OPENAI_SCORE_SUMMARY, $scoreSummary);
    }

    /**
     * Sets excluded for training flag for the review
     *
     * @param int $excludedForTraining
     * @return ReviewInterface
     */
    public function setOpenAiExcludedForTraining(int $excludedForTraining): ReviewInterface
    {
        return $this->setData(self::OPENAI_EXCLUDED_FOR_TRAINING, $excludedForTraining);
    }
}
