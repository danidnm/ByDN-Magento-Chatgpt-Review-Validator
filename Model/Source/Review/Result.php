<?php

namespace DanielNavarro\ChatGptReviewValidator\Model\Source\Review;

class Result implements \Magento\Framework\Option\ArrayInterface
{
    public const REVIEW_RESULT_PENDING = 'pending';
    public const REVIEW_RESULT_FLAGGED = 'flagged';
    public const REVIEW_RESULT_OK = 'ok';

    /**
     * Assign result name for each moderation result
     * @var string[]
     */
    private $labels = [
        self::REVIEW_RESULT_PENDING => 'Pending',
        self::REVIEW_RESULT_FLAGGED => 'Problems found',
        self::REVIEW_RESULT_OK => 'OK',
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
     * Returns options and labels to be used as a colum in grids
     *
     * @return array
     */
    public function toColumnOptionArray()
    {
        $options = [
            self::REVIEW_RESULT_PENDING => __($this->labels[self::REVIEW_RESULT_PENDING]),
            self::REVIEW_RESULT_FLAGGED => __($this->labels[self::REVIEW_RESULT_FLAGGED]),
            self::REVIEW_RESULT_OK => __($this->labels[self::REVIEW_RESULT_OK]),
        ];

        return $options;
    }

    /**
     * Return result label for the result
     *
     * @param string $result
     * @return \Magento\Framework\Phrase
     */
    public function getLabel(string $result)
    {
        return (isset($this->labels[$result])) ? __($this->labels[$result]) : __('Unknown');
    }
}
