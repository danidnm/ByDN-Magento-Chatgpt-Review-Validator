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
    public function getReviewId(): ?int
    {
        return $this->getData(self::REVIEW_ID);
    }

    /**
     * @return string
     */
    public function getValidatedAt(): string
    {
        return $this->getData(self::VALIDATED_AT);
    }

    /**
     * @return string
     */
    public function getProblems(): string
    {
        return $this->getData(self::PROBLEMS);
    }

    /**
     * @return string
     */
    public function getResult(): string
    {
        return $this->getData(self::RESULT);
    }

    /**
     * @return int
     */
    public function getManuallyValidated(): int
    {
        return $this->getData(self::MANUALLY_VALIDATED);
    }

    /**
     * @return int
     */
    public function getExcludedForTraining(): int
    {
        return $this->getData(self::EXCLUDED_FOR_TRAINING);
    }

    /**
     * @param int $reviewId
     * @return ReviewInterface
     */
    public function setReivewId(int $reviewId): ReviewInterface
    {
        return $this->setData(self::ID, $reviewId);
    }

    /**
     * @param string $validatedAt
     * @return ReviewInterface
     */
    public function setValidatedAt(string $validatedAt): ReviewInterface
    {
        return $this->setData(self::VALIDATED_AT, $validatedAt);
    }

    /**
     * @param string $problems
     * @return ReviewInterface
     */
    public function setProblems(string $problems): ReviewInterface
    {
        return $this->setData(self::PROBLEMS, $problems);
    }

    /**
     * @param string $result
     * @return ReviewInterface
     */
    public function setResult(string $result): ReviewInterface
    {
        return $this->setData(self::RESULT, $result);
    }

    /**
     * @param int $manuallyValidated
     * @return ReviewInterface
     */
    public function setManuallyValidated(int $manuallyValidated): ReviewInterface
    {
        return $this->setData(self::MANUALLY_VALIDATED, $manuallyValidated);
    }

    /**
     * @param int $excludedForTraining
     * @return ReviewInterface
     */
    public function setExcludedForTraining(int $excludedForTraining): ReviewInterface
    {
        return $this->setData(self::EXCLUDED_FOR_TRAINING, $excludedForTraining);
    }
}
