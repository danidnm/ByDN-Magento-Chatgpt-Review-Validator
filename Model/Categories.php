<?php

namespace Bydn\OpenAiReviewValidator\Model;

class Categories implements \Magento\Framework\Data\OptionSourceInterface
{
    public const CATEGORY_SEXUAL = 'sexual';
    public const CATEGORY_HATE = 'hate';
    public const CATEGORY_HARASSMENT = 'harassment';
    public const CATEGORY_SELF_HARM = 'selfharm';
    public const CATEGORY_THREATENING = 'threatening';
    public const CATEGORY_VIOLENCE = 'violence';
    public const CATEGORY_SPAM = 'spam';
    public const CATEGORY_UNRELATED = 'unrelated';

    /**
     * Assign category name for each moderation category
     * @var string[]
     */
    private $labels = [
        self::CATEGORY_SEXUAL => 'Sexual language',
        self::CATEGORY_HATE => 'Hate',
        self::CATEGORY_HARASSMENT => 'Harassment',
        self::CATEGORY_SELF_HARM => 'Self-harm',
        self::CATEGORY_THREATENING => 'Threatening',
        self::CATEGORY_VIOLENCE => 'Violence',
        self::CATEGORY_SPAM => 'Spam',
        self::CATEGORY_UNRELATED => 'Unrelated',
    ];

    /**
     * Returns options and labels to be used as a source attribute
     *
     * @return array[]
     */
    public function toOptionArray()
    {
        return [
            [
                'label' => __($this->labels[self::CATEGORY_SEXUAL]),
                self::CATEGORY_SEXUAL,
            ],
            [
                'label' => __($this->labels[self::CATEGORY_HATE]),
                self::CATEGORY_HATE,
            ],
            [
                'label' => __($this->labels[self::CATEGORY_HARASSMENT]),
                self::CATEGORY_HARASSMENT,
            ],
            [
                'label' => __($this->labels[self::CATEGORY_SELF_HARM]),
                self::CATEGORY_SELF_HARM,
            ],
            [
                'label' => __($this->labels[self::CATEGORY_THREATENING]),
                self::CATEGORY_THREATENING,
            ],
            [
                'label' => __($this->labels[self::CATEGORY_VIOLENCE]),
                self::CATEGORY_VIOLENCE,
            ],
            [
                'label' => __($this->labels[self::CATEGORY_SPAM]),
                self::CATEGORY_SPAM,
            ],
            [
                'label' => __($this->labels[self::CATEGORY_UNRELATED]),
                self::CATEGORY_UNRELATED,
            ],
        ];
    }

    /**
     * Return label corresponding to the category
     *
     * @param string $category
     * @return \Magento\Framework\Phrase
     */
    public function getLabel(string $category)
    {
        return (isset($this->labels[$category])) ? __($this->labels[$category]) : __('Unknown');
    }
}
