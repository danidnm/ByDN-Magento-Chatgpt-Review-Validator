<?php

namespace Bydn\ChatGptReviewValidator\Api\Data;

interface ReviewInterface
{
    public const ID = 'id';
    public const GPT_REVIEW_ID = 'gpt_review_id';
    public const GPT_STATUS = 'gpt_status';
    public const GPT_VALIDATED_AT = 'gpt_validated_at';
    public const GPT_RESULT = 'gpt_result';
    public const GPT_PROBLEMS = 'gpt_problems';
    public const GPT_SCORE_SUMMARY = 'gpt_score_summary';
    public const GPT_EXCLUDED_FOR_TRAINING = 'gpt_excluded_for_training';

    /**
     * Returns entity ID (this ID is not the review ID but the ID of the extra info table)
     *
     * @return null|int
     */
    public function getId(): ?int;

    /**
     * Returns review id for this entity
     *
     * @return null|int
     */
    public function getGptReviewId(): ?int;

    /**
     * Returns OpenAI validation status
     *
     * @return string
     */
    public function getGptStatus(): string;

    /**
     * Returns the date when the review was moderated
     *
     * @return string|null
     */
    public function getGptValidatedAt(): string|null;

    /**
     * Return the OpenAI validation result
     *
     * @return string
     */
    public function getGptResult(): string;

    /**
     * Returns OpenAI problems found
     *
     * @return string|null
     */
    public function getGptProblems(): string|null;

    /**
     * Returns scores for each category
     *
     * @return string|null
     */
    public function getGptScoreSummary(): string|null;

    /**
     * Returns if this review is excluded for training
     *
     * @return int|null
     */
    public function getGptExcludedForTraining(): ?int;

    /**
     * Sets the review ID
     *
     * @param int $reviewId
     * @return ReviewInterface
     */
    public function setGptReviewId(int $reviewId): ReviewInterface;

    /**
     * Set review OpenAI moderation status
     *
     * @param string $status
     * @return ReviewInterface
     */
    public function setGptStatus(string $status): ReviewInterface;

    /**
     * Sets the OpenAI moderation date for this review
     *
     * @param mixed $validatedAt
     * @return ReviewInterface
     */
    public function setGptValidatedAt(mixed $validatedAt): ReviewInterface;

    /**
     * Set OpenAI moderation result for the review
     *
     * @param string $result
     * @return ReviewInterface
     */
    public function setGptResult(string $result): ReviewInterface;

    /**
     * Set moderation problems found for the review
     *
     * @param mixed $problems
     * @return ReviewInterface
     */
    public function setGptProblems(mixed $problems): ReviewInterface;

    /**
     * Sets moderation scores for the review (should be a json)
     *
     * @param mixed $scoreSummary
     * @return ReviewInterface
     */
    public function setGptScoreSummary(mixed $scoreSummary): ReviewInterface;

    /**
     * Sets excluded for training flag for the review
     *
     * @param int $excludedForTraining
     * @return ReviewInterface
     */
    public function setGptExcludedForTraining(int $excludedForTraining): ReviewInterface;
}
