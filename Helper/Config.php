<?php

namespace DanielNavarro\ChatGptReviewValidator\Helper;

class Config extends \Magento\Framework\App\Helper\AbstractHelper
{
    const PATH_CHATGPT_REVIEW_VALIDATOR_ENABLE = 'danielnavarro_chatgptreviewvalidator/general/enable';
    const PATH_CHATGPT_REVIEW_VALIDATOR_AUTO = 'danielnavarro_chatgptreviewvalidator/general/auto_validation';
    const PATH_CHATGPT_REVIEW_SCORES_PATH = 'danielnavarro_chatgptreviewvalidator/scores/';

    private $categories = [
        \DanielNavarro\ChatGptReviewValidator\Model\Categories::CATEGORY_SEXUAL,
        \DanielNavarro\ChatGptReviewValidator\Model\Categories::CATEGORY_HATE,
        \DanielNavarro\ChatGptReviewValidator\Model\Categories::CATEGORY_HARASSMENT,
        \DanielNavarro\ChatGptReviewValidator\Model\Categories::CATEGORY_SELF_HARM,
        \DanielNavarro\ChatGptReviewValidator\Model\Categories::CATEGORY_THREATENING,
        \DanielNavarro\ChatGptReviewValidator\Model\Categories::CATEGORY_VIOLENCE,
    ];

    /**
     * Check if ChatGPT integration is enabled
     * @param $store_id
     * @return mixed
     */
    public function isEnabled($store_id = null) {
        return $this->scopeConfig->getValue(
            self::PATH_CHATGPT_REVIEW_VALIDATOR_ENABLE,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store_id
        );
    }

    /**
     * @param $storeId
     * @return mixed
     */
    public function isAutoValidationEnabled($storeId = null) {
        return $this->scopeConfig->getValue(
            self::PATH_CHATGPT_REVIEW_VALIDATOR_AUTO,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    public function getMaximumScores($storeId = null) {
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
