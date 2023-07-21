<?php

namespace DanielNavarro\ChatGptReviewValidator\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Review extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('dn_chatgpt_review_scores', 'id');
    }
}
