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
    const GPT_SCORE_SUMMARY = 'gpt_score_summary';
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
     * @return string|null
     */
    public function getGptValidatedAt(): string|null;

    /**
     * @return string
     */
    public function getGptResult(): string;

    /**
     * @return string|null
     */
    public function getGptProblems(): string|null;

    /**
     * @return string|null
     */
    public function getGptScoreSummary(): string|null;

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
     * @param mixed $validatedAt
     * @return ReviewInterface
     */
    public function setGptValidatedAt(mixed $validatedAt): ReviewInterface;

    /**
     * @param string $result
     * @return ReviewInterface
     */
    public function setGptResult(string $result): ReviewInterface;

    /**
     * @param mixed $problems
     * @return ReviewInterface
     */
    public function setGptProblems(mixed $problems): ReviewInterface;

    /**
     * @param mixed $scoreSummary
     * @return ReviewInterface
     */
    public function setGptScoreSummary(mixed $scoreSummary): ReviewInterface;

    /**
     * @param int $excludedForTraining
     * @return ReviewInterface
     */
    public function setGptExcludedForTraining(int $excludedForTraining): ReviewInterface;
}
