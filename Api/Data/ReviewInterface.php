<?php

namespace DanielNavarro\ChatGptReviewValidator\Api\Data;

interface ReviewInterface
{
    const ID = 'id';
    const GPT_REVIEW_ID = 'gpt_review_id';
    const GPT_STATUS = 'gpt_status';
    const GPT_VALIDATED_AT = 'gpt_validated_at';
    const GPT_RESULT = 'gpt_result';
    const GPT_PROBLEMS = 'gpt_problems';
    const GPT_EXCLUDED_FOR_TRAINING = 'gpt_excluded_for_training';

    /**
     * @return null|int
     */
    public function getId(): ?int;

    /**
     * @return null|int
     */
    public function getGptReviewId(): ?int;

    /**
     * @return string
     */
    public function getGptStatus(): string;

    /**
     * @return string
     */
    public function getGptValidatedAt(): string;

    /**
     * @return string
     */
    public function getGptResult(): string;

    /**
     * @return string
     */
    public function getGptProblems(): string;

    /**
     * @return int|null
     */
    public function getGptExcludedForTraining(): ?int;

    /**
     * @param string $status
     * @return ReviewInterface
     */
    public function setGptStatus(string $status): ReviewInterface;

    /**
     * @param string $validatedAt
     * @return ReviewInterface
     */
    public function setGptValidatedAt(string $validatedAt): ReviewInterface;

    /**
     * @param string $result
     * @return ReviewInterface
     */
    public function setGptResult(string $result): ReviewInterface;

    /**
     * @param string $problems
     * @return ReviewInterface
     */
    public function setGptProblems(string $problems): ReviewInterface;

    /**
     * @param int $excludedForTraining
     * @return ReviewInterface
     */
    public function setGptExcludedForTraining(int $excludedForTraining): ReviewInterface;
}
