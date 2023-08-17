<?php

namespace Bydn\OpenAiReviewValidator\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Review extends AbstractDb
{
    /**
     * Initialize table for the resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('bydn_open_ai_review_scores', 'id');
    }
}
