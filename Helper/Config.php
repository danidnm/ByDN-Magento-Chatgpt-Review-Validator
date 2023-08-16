<?php

namespace DanielNavarro\ChatGptReviewValidator\Helper;

class Config extends \Magento\Framework\App\Helper\AbstractHelper
{
    public const PATH_CHATGPT_REVIEW_VALIDATOR_ENABLE = 'danielnavarro_chatgptreviewvalidator/general/enable';
    public const PATH_CHATGPT_REVIEW_VALIDATOR_AUTO = 'danielnavarro_chatgptreviewvalidator/general/auto_validation';
    public const PATH_CHATGPT_REVIEW_SCORES_PATH = 'danielnavarro_chatgptreviewvalidator/scores/';

    /**
     * List of moderation categories
     * @var array
     */
    private $categories = [
        \DanielNavarro\ChatGptReviewValidator\Model\Categories::CATEGORY_SEXUAL,
        \DanielNavarro\ChatGptReviewValidator\Model\Categories::CATEGORY_HATE,
        \DanielNavarro\ChatGptReviewValidator\Model\Categories::CATEGORY_HARASSMENT,
        \DanielNavarro\ChatGptReviewValidator\Model\Categories::CATEGORY_SELF_HARM,
        \DanielNavarro\ChatGptReviewValidator\Model\Categories::CATEGORY_THREATENING,
        \DanielNavarro\ChatGptReviewValidator\Model\Categories::CATEGORY_VIOLENCE,
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
            self::PATH_CHATGPT_REVIEW_VALIDATOR_ENABLE,
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
            self::PATH_CHATGPT_REVIEW_VALIDATOR_AUTO,
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
                self::PATH_CHATGPT_REVIEW_SCORES_PATH . $category,
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                $storeId
            );
        }
        return $data;
    }
}
