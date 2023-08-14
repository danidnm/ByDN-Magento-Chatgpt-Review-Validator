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

    public function toOptionArray()
    {
        return [
            [
                'label' => __('Sexual'),
                self::CATEGORY_SEXUAL,
            ],
            [
                'label' => __('Hate'),
                self::CATEGORY_HATE,
            ],
            [
                'label' => __('Harassment'),
                self::CATEGORY_HARASSMENT,
            ],
            [
                'label' => __('Self-harm'),
                self::CATEGORY_SELF_HARM,
            ],
            [
                'label' => __('Threatening'),
                self::CATEGORY_THREATENING,
            ],
            [
                'label' => __('Violence'),
                self::CATEGORY_VIOLENCE,
            ],
        ];
    }
}
