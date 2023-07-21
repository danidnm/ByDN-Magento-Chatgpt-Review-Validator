<?php

namespace DanielNavarro\ChatGptReviewValidator\Model;

use DanielNavarro\ChatGptReviewValidator\Api\Data\ReviewInterface;
use Magento\Framework\Model\AbstractExtensibleModel;

class Review extends AbstractExtensibleModel implements ReviewInterface
{
    protected function _construct()
    {
        $this->_init(\DanielNavarro\ChatGptReviewValidator\Model\ResourceModel\Review::class);
        $this->setIdFieldName('id');
    }

    /**
     * @return null|int
     */
    public function getId(): ?int
    {
        return $this->getData(self::ID);
    }

    /**
     * @return null|int
     */
    public function getGptReviewId(): ?int
    {
        return $this->getData(self::GPT_REVIEW_ID);
    }

    /**
     * @return string
     */
    public function getGptStatus(): string
    {
        return $this->getData(self::GPT_STATUS);
    }

    /**
     * @return string
     */
    public function getGptValidatedAt(): string
    {
        return $this->getData(self::GPT_VALIDATED_AT);
    }

    /**
     * @return string
     */
    public function getGptResult(): string
    {
        return $this->getData(self::GPT_RESULT);
    }

    /**
     * @return string
     */
    public function getGptProblems(): string
    {
        return $this->getData(self::GPT_PROBLEMS);
    }

    /**
     * @return int
     */
    public function getGptExcludedForTraining(): int
    {
        return $this->getData(self::GPT_EXCLUDED_FOR_TRAINING);
    }

    /**
     * @param int $reviewId
     * @return ReviewInterface
     */
    public function setGptReviewId(int $reviewId): ReviewInterface
    {
        return $this->setData(self::GPT_REVIEW_ID, $reviewId);
    }

    /**
     * @param string $status
     * @return ReviewInterface
     */
    public function setGptStatus(string $status): ReviewInterface
    {
        return $this->setData(self::GPT_STATUS, $status);
    }

    /**
     * @param string $validatedAt
     * @return ReviewInterface
     */
    public function setGptValidatedAt(string $validatedAt): ReviewInterface
    {
        return $this->setData(self::GPT_VALIDATED_AT, $validatedAt);
    }

    /**
     * @param string $result
     * @return ReviewInterface
     */
    public function setGptResult(string $result): ReviewInterface
    {
        return $this->setData(self::GPT_RESULT, $result);
    }

    /**
     * @param string $problems
     * @return ReviewInterface
     */
    public function setGptProblems(string $problems): ReviewInterface
    {
        return $this->setData(self::GPT_PROBLEMS, $problems);
    }

    /**
     * @param int $excludedForTraining
     * @return ReviewInterface
     */
    public function setGptExcludedForTraining(int $excludedForTraining): ReviewInterface
    {
        return $this->setData(self::GPT_EXCLUDED_FOR_TRAINING, $excludedForTraining);
    }
}
