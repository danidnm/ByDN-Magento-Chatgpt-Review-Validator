<?php

namespace DanielNavarro\ChatGptReviewValidator\Model\ResourceModel\Review;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(
            \DanielNavarro\ChatGptReviewValidator\Model\Review::class,
            \DanielNavarro\ChatGptReviewValidator\Model\ResourceModel\Review::class
        );
    }
}
