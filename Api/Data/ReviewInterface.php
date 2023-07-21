<?php

namespace DanielNavarro\ChatGptReviewValidator\Api\Data;

interface ReviewInterface
{
    const ID = 'id';
    const REVIEW_ID = 'review_id';
    const VALIDATED_AT = 'validated_at';
    const PROBLEMS = 'problems';
    const RESULT = 'result';
    const MANUALLY_VALIDATED = 'manually_validated';
    const EXCLUDED_FOR_TRAINING = 'excluded_for_training';

    /**
     * @return null|int
     */
    public function getId(): ?int;

    /**
     * @return null|int
     */
    public function getReviewId(): ?int;

    /**
     * @return string
     */
    public function getValidatedAt(): string;

    /**
     * @return string
     */
    public function getProblems(): string;

    /**
     * @return string
     */
    public function getResult(): string;

    /**
     * @return int|null
     */
    public function getManuallyValidated(): ?int;

    /**
     * @return int|null
     */
    public function getExcludedForTraining(): ?int;

    /**
     * @param string $validatedAt
     * @return ReviewInterface
     */
    public function setValidatedAt(string $validatedAt): ReviewInterface;

    /**
     * @param string $problems
     * @return ReviewInterface
     */
    public function setProblems(string $problems): ReviewInterface;

    /**
     * @param string $result
     * @return ReviewInterface
     */
    public function setResult(string $result): ReviewInterface;

    /**
     * @param int $manuallyValidated
     * @return ReviewInterface
     */
    public function setManuallyValidated(int $manuallyValidated): ReviewInterface;

    /**
     * @param int $excludedForTraining
     * @return ReviewInterface
     */
    public function setExcludedForTraining(int $excludedForTraining): ReviewInterface;
}
