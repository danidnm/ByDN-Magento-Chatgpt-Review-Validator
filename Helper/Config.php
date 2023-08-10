<?php

namespace DanielNavarro\ChatGptReviewValidator\Helper;

class Config extends \Magento\Framework\App\Helper\AbstractHelper
{
    const PATH_CHATGPT_REVIEW_VALIDATOR_ENABLE = 'danielnavarro_chatgptreviewvalidator/general/enable';
    const PATH_CHATGPT_REVIEW_VALIDATOR_AUTO = 'danielnavarro_chatgptreviewvalidator/general/auto_validation';

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
     * @param $store_id
     * @return mixed
     */
    public function isAutoValidationEnabled($store_id = null) {
        return $this->scopeConfig->getValue(
            self::PATH_CHATGPT_REVIEW_VALIDATOR_AUTO,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store_id
        );
    }
}
