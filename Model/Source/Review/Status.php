<?php

namespace Bydn\OpenAiReviewValidator\Model\Source\Review;

class Status implements \Magento\Framework\Option\ArrayInterface
{
    public const REVIEW_STATUS_PENDING = 'pending';
    public const REVIEW_STATUS_PROCESSED = 'processed';
    public const REVIEW_STATUS_MANUAL = 'manual';

    /**
     * Assign status name for each status
     * @var string[]
     */
    private $labels = [
        self::REVIEW_STATUS_PENDING => 'Pending',
        self::REVIEW_STATUS_PROCESSED => 'Processed',
        self::REVIEW_STATUS_MANUAL => 'Manual',
    ];

    /**
     * Returns options and labels to be used as a source attribute
     *
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
     * Returns options and labels to be used as a colum in grids
     *
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
     * Return corresponding label for the status
     *
     * @param string $status
     * @return \Magento\Framework\Phrase
     */
    public function getLabel(string $status)
    {
        return (isset($this->labels[$status])) ? __($this->labels[$status]) : __('Unknown');
    }
}
