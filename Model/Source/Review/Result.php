<?php

namespace DanielNavarro\ChatGptReviewValidator\Model\Source\Review;

class Result implements \Magento\Framework\Option\ArrayInterface
{
    const REVIEW_RESULT_PENDING = 'pending';
    const REVIEW_RESULT_FLAGGED = 'flagged';
    const REVIEW_RESULT_OK = 'ok';

    private $labels = [
        self::REVIEW_RESULT_PENDING => 'Pending',
        self::REVIEW_RESULT_FLAGGED => 'Flagged',
        self::REVIEW_RESULT_OK => 'OK',
    ];

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $options = [
            [
                'label' => __($this->labels[self::REVIEW_RESULT_PENDING]),
                self::REVIEW_RESULT_PENDING,
            ],
            [
                'label' => __($this->labels[self::REVIEW_RESULT_FLAGGED]),
                self::REVIEW_RESULT_FLAGGED,
            ],
            [
                'label' => __($this->labels[self::REVIEW_RESULT_OK]),
                self::REVIEW_RESULT_OK,
            ],
        ];

        return $options;
    }

    /**
     * Return result label for the result
     * @param $result
     * @return \Magento\Framework\Phrase
     */
    public function getLabel($result) {

        return (isset($this->labels[$result])) ? __($this->labels[$result]) : __('Unknown');
    }
}
