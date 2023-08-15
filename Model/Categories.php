<?php

namespace DanielNavarro\ChatGptReviewValidator\Model;

class Categories implements \Magento\Framework\Data\OptionSourceInterface
{
    const CATEGORY_SEXUAL = 'sexual';
    const CATEGORY_HATE = 'hate';
    const CATEGORY_HARASSMENT = 'harassment';
    const CATEGORY_SELF_HARM = 'selfharm';
    const CATEGORY_THREATENING = 'threatening';
    const CATEGORY_VIOLENCE = 'violence';

    private $labels = [
        self::CATEGORY_SEXUAL => 'Sexual language',
        self::CATEGORY_HATE => 'Hate',
        self::CATEGORY_HARASSMENT => 'Harassment',
        self::CATEGORY_SELF_HARM => 'Self-harm',
        self::CATEGORY_THREATENING => 'Threatening',
        self::CATEGORY_VIOLENCE => 'Violence',
    ];

    /**
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
        ];
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
