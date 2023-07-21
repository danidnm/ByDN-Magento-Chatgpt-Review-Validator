<?php

namespace DanielNavarro\ChatGptReviewValidator\Helper;

class Config extends \Magento\Framework\App\Helper\AbstractHelper
{
    const PATH_CHATGPT_REVIEW_VALIDATOR_ENABLE = 'danielnavarro_chatgptreviewvalidator/general/enable';

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
}
