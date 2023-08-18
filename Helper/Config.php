<?php

namespace Bydn\OpenAiReviewValidator\Helper;

class Config extends \Magento\Framework\App\Helper\AbstractHelper
{
    public const PATH_OPENAI_REVIEW_VALIDATOR_ENABLE = 'bydn_openaireviewvalidator/general/enable';
    public const PATH_OPENAI_REVIEW_VALIDATOR_AUTO = 'bydn_openaireviewvalidator/general/auto_validation';
    public const PATH_OPENAI_REVIEW_LANGUAGE_ENABLE = 'bydn_openaireviewvalidator/language/enable';
    public const PATH_OPENAI_REVIEW_LANGUAGE_CATEGORIES = 'bydn_openaireviewvalidator/language/';
    public const PATH_OPENAI_REVIEW_SPAM_ENABLE = 'bydn_openaireviewvalidator/spam/enable';
    public const PATH_OPENAI_REVIEW_SPAM_THRESHOLD = 'bydn_openaireviewvalidator/spam/threshold';
    public const PATH_OPENAI_REVIEW_UNRELATED_ENABLE = 'bydn_openaireviewvalidator/unrelated/enable';
    public const PATH_OPENAI_REVIEW_UNRELATED_TEXT = 'bydn_openaireviewvalidator/unrelated/text';
    public const PATH_OPENAI_REVIEW_UNRELATED_THRESHOLD = 'bydn_openaireviewvalidator/unrelated/threshold';

    /**
     * List of moderation categories
     * @var array
     */
    private $languageCategories = [
        \Bydn\OpenAiReviewValidator\Model\Categories::CATEGORY_SEXUAL,
        \Bydn\OpenAiReviewValidator\Model\Categories::CATEGORY_HATE,
        \Bydn\OpenAiReviewValidator\Model\Categories::CATEGORY_HARASSMENT,
        \Bydn\OpenAiReviewValidator\Model\Categories::CATEGORY_SELF_HARM,
        \Bydn\OpenAiReviewValidator\Model\Categories::CATEGORY_THREATENING,
        \Bydn\OpenAiReviewValidator\Model\Categories::CATEGORY_VIOLENCE,
    ];

    /**
     * Check if OpenAI integration is enabled
     *
     * @param int|null|string $store_id
     * @return mixed
     */
    public function isEnabled($store_id = null)
    {
        return $this->scopeConfig->getValue(
            self::PATH_OPENAI_REVIEW_VALIDATOR_ENABLE,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store_id
        );
    }

    /**
     * Returns if validation for abusive language is enabled
     *
     * @param int|null|string $storeId
     * @return mixed
     */
    public function isLanguageValidationEnabled($storeId = null)
    {
        return $this->scopeConfig->getValue(
            self::PATH_OPENAI_REVIEW_LANGUAGE_ENABLE,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Returns if validation for abusive language is enabled
     *
     * @param int|null|string $storeId
     * @return mixed
     */
    public function isSpamValidationEnabled($storeId = null)
    {
        return $this->scopeConfig->getValue(
            self::PATH_OPENAI_REVIEW_SPAM_ENABLE,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Returns if validation for abusive language is enabled
     *
     * @param int|null|string $storeId
     * @return mixed
     */
    public function isUnrelatedValidationEnabled($storeId = null)
    {
        return $this->scopeConfig->getValue(
            self::PATH_OPENAI_REVIEW_UNRELATED_ENABLE,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Returns the text to be used for validation of unrelated content
     *
     * @param int|null|string $storeId
     * @return mixed
     */
    public function getUnrelatedTextToMatchWith($storeId = null)
    {
        return $this->scopeConfig->getValue(
            self::PATH_OPENAI_REVIEW_UNRELATED_TEXT,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Returns if auto validation is enabled.
     * Auto-validation means review status is automatically changed from pending to approved or rejected
     * when validated by OpenAI
     *
     * @param int|null|string $storeId
     * @return mixed
     */
    public function isAutoValidationEnabled($storeId = null)
    {
        return $this->scopeConfig->getValue(
            self::PATH_OPENAI_REVIEW_VALIDATOR_AUTO,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Returns configured maximum scores for each moderation category
     *
     * @param int|null|string $storeId
     * @return array
     */
    public function getMaximumScores($storeId = null)
    {
        $data = [];

        // Language categories
        foreach ($this->languageCategories as $category) {
            $data[$category] = $this->scopeConfig->getValue(
                self::PATH_OPENAI_REVIEW_LANGUAGE_CATEGORIES . $category,
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                $storeId
            );
        }

        // Spam
        $data[\Bydn\OpenAiReviewValidator\Model\Categories::CATEGORY_SPAM] = $this->scopeConfig->getValue(
            self::PATH_OPENAI_REVIEW_SPAM_THRESHOLD,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );

        // Unrelated
        $data[\Bydn\OpenAiReviewValidator\Model\Categories::CATEGORY_UNRELATED] = $this->scopeConfig->getValue(
            self::PATH_OPENAI_REVIEW_UNRELATED_THRESHOLD,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );

        return $data;
    }
}
