<?php

namespace Bydn\OpenAiReviewValidator\Model\ResourceModel\Review;

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
            \Bydn\OpenAiReviewValidator\Model\Review::class,
            \Bydn\OpenAiReviewValidator\Model\ResourceModel\Review::class
        );
    }
}
