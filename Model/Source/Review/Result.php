<?php

namespace DanielNavarro\ChatGptReviewValidator\Model\Source\Review;

class Result implements \Magento\Framework\Option\ArrayInterface
{
    const REVIEW_RESULT_PENDING = 'pending';
    const REVIEW_RESULT_FLAGGED = 'flagged';
    const REVIEW_RESULT_OK = 'ok';

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $options = [
            [
                'label' => __('Pending'),
                \DanielNavarro\ChatGptReviewValidator\Model\Source\Review\Result::REVIEW_RESULT_PENDING,
            ],
            [
                'label' => __('Flagged'),
                \DanielNavarro\ChatGptReviewValidator\Model\Source\Review\Result::REVIEW_RESULT_FLAGGED,
            ],
            [
                'label' => __('OK'),
                \DanielNavarro\ChatGptReviewValidator\Model\Source\Review\Result::REVIEW_RESULT_OK,
            ],
        ];

        return $options;
    }
}
