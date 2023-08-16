<?php

namespace DanielNavarro\ChatGptReviewValidator\Model\ResourceModel\Review;

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
            \DanielNavarro\ChatGptReviewValidator\Model\Review::class,
            \DanielNavarro\ChatGptReviewValidator\Model\ResourceModel\Review::class
        );
    }
}
