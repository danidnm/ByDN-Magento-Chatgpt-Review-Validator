<?php

namespace DanielNavarro\ChatGptReviewValidator\Model\Source\Review;

class Status implements \Magento\Framework\Option\ArrayInterface
{
    const REVIEW_STATUS_PENDING = 'pending';
    const REVIEW_STATUS_PROCESSED = 'processed';
    const REVIEW_STATUS_MANUAL = 'manual';

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $options = [
            [
                'label' => __('Pending'),
                \DanielNavarro\ChatGptReviewValidator\Model\Source\Review\Status::REVIEW_STATUS_PENDING,
            ],
            [
                'label' => __('Processed'),
                \DanielNavarro\ChatGptReviewValidator\Model\Source\Review\Status::REVIEW_STATUS_PROCESSED,
            ],
            [
                'label' => __('Manual'),
                \DanielNavarro\ChatGptReviewValidator\Model\Source\Review\Status::REVIEW_STATUS_MANUAL,
            ],
        ];

        return $options;
    }
}
