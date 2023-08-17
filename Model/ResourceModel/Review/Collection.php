<?php

namespace Bydn\ChatGptReviewValidator\Model\ResourceModel\Review;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * Initialize collection model and resource
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            \Bydn\ChatGptReviewValidator\Model\Review::class,
            \Bydn\ChatGptReviewValidator\Model\ResourceModel\Review::class
        );
    }
}
