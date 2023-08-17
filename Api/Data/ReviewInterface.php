<?php

namespace Bydn\OpenAiReviewValidator\Api\Data;

interface ReviewInterface
{
    public const ID = 'id';
    public const OPENAI_REVIEW_ID = 'open_ai_review_id';
    public const OPENAI_STATUS = 'open_ai_status';
    public const OPENAI_VALIDATED_AT = 'open_ai_validated_at';
    public const OPENAI_RESULT = 'open_ai_result';
    public const OPENAI_PROBLEMS = 'open_ai_problems';
    public const OPENAI_SCORE_SUMMARY = 'open_ai_score_summary';
    public const OPENAI_EXCLUDED_FOR_TRAINING = 'open_ai_excluded_for_training';

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
    public function getOpenAiReviewId(): ?int;

    /**
     * Returns OpenAI validation status
     *
     * @return string
     */
    public function getOpenAiStatus(): string;

    /**
     * Returns the date when the review was moderated
     *
     * @return string|null
     */
    public function getOpenAiValidatedAt(): string|null;

    /**
     * Return the OpenAI validation result
     *
     * @return string
     */
    public function getOpenAiResult(): string;

    /**
     * Returns OpenAI problems found
     *
     * @return string|null
     */
    public function getOpenAiProblems(): string|null;

    /**
     * Returns scores for each category
     *
     * @return string|null
     */
    public function getOpenAiScoreSummary(): string|null;

    /**
     * Returns if this review is excluded for training
     *
     * @return int|null
     */
    public function getOpenAiExcludedForTraining(): ?int;

    /**
     * Sets the review ID
     *
     * @param int $reviewId
     * @return ReviewInterface
     */
    public function setOpenAiReviewId(int $reviewId): ReviewInterface;

    /**
     * Set review OpenAI moderation status
     *
     * @param string $status
     * @return ReviewInterface
     */
    public function setOpenAiStatus(string $status): ReviewInterface;

    /**
     * Sets the OpenAI moderation date for this review
     *
     * @param mixed $validatedAt
     * @return ReviewInterface
     */
    public function setOpenAiValidatedAt(mixed $validatedAt): ReviewInterface;

    /**
     * Set OpenAI moderation result for the review
     *
     * @param string $result
     * @return ReviewInterface
     */
    public function setOpenAiResult(string $result): ReviewInterface;

    /**
     * Set moderation problems found for the review
     *
     * @param mixed $problems
     * @return ReviewInterface
     */
    public function setOpenAiProblems(mixed $problems): ReviewInterface;

    /**
     * Sets moderation scores for the review (should be a json)
     *
     * @param mixed $scoreSummary
     * @return ReviewInterface
     */
    public function setOpenAiScoreSummary(mixed $scoreSummary): ReviewInterface;

    /**
     * Sets excluded for training flag for the review
     *
     * @param int $excludedForTraining
     * @return ReviewInterface
     */
    public function setOpenAiExcludedForTraining(int $excludedForTraining): ReviewInterface;
}
