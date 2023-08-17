<?php

namespace Bydn\OpenAiReviewValidator\Helper;

class Config extends \Magento\Framework\App\Helper\AbstractHelper
{
    public const PATH_OPENAI_REVIEW_VALIDATOR_ENABLE = 'bydn_openaireviewvalidator/general/enable';
    public const PATH_OPENAI_REVIEW_VALIDATOR_AUTO = 'bydn_openaireviewvalidator/general/auto_validation';
    public const PATH_OPENAI_REVIEW_SCORES_PATH = 'bydn_openaireviewvalidator/scores/';

    /**
     * List of moderation categories
     * @var array
     */
    private $categories = [
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
        foreach ($this->categories as $category) {
            $data[$category] = $this->scopeConfig->getValue(
                self::PATH_OPENAI_REVIEW_SCORES_PATH . $category,
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                $storeId
            );
        }
        return $data;
    }
}
