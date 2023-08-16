<?php

namespace DanielNavarro\ChatGptReviewValidator\Model\Source\Review;

class Status implements \Magento\Framework\Option\ArrayInterface
{
    const REVIEW_STATUS_PENDING = 'pending';
    const REVIEW_STATUS_PROCESSED = 'processed';
    const REVIEW_STATUS_MANUAL = 'manual';

    private $labels = [
        self::REVIEW_STATUS_PENDING => 'Pending',
        self::REVIEW_STATUS_PROCESSED => 'Processed',
        self::REVIEW_STATUS_MANUAL => 'Manual',
    ];

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $options = [
            [
                'label' => __($this->labels[self::REVIEW_STATUS_PENDING]),
                self::REVIEW_STATUS_PENDING,
            ],
            [
                'label' => __($this->labels[self::REVIEW_STATUS_PROCESSED]),
                self::REVIEW_STATUS_PROCESSED,
            ],
            [
                'label' => __($this->labels[self::REVIEW_STATUS_MANUAL]),
                self::REVIEW_STATUS_MANUAL,
            ],
        ];

        return $options;
    }

    /**
     * @return array
     */
    public function toColumnOptionArray()
    {
        $options = [
            self::REVIEW_STATUS_PENDING => __($this->labels[self::REVIEW_STATUS_PENDING]),
            self::REVIEW_STATUS_PROCESSED => __($this->labels[self::REVIEW_STATUS_PROCESSED]),
            self::REVIEW_STATUS_MANUAL => __($this->labels[self::REVIEW_STATUS_MANUAL]),
        ];

        return $options;
    }

    /**
     * Return status label for the status
     * @param $status
     * @return \Magento\Framework\Phrase
     */
    public function getLabel($status) {

        return (isset($this->labels[$status])) ? __($this->labels[$status]) : __('Unknown');
    }
}
