<?php

namespace DanielNavarro\ChatGptReviewValidator\Plugin\Magento\Review\Block\Adminhtml\Edit;

class Form
{
    /**
     * @var \Magento\Framework\Registry
     */
    private $registry;

    /**
     * @var \DanielNavarro\ChatGptReviewValidator\Model\Source\Review\Status
     */
    private $gptStatus;

    /**
     * @var \DanielNavarro\ChatGptReviewValidator\Model\Source\Review\Result
     */
    private $gptResult;

    /**
     * @var \DanielNavarro\ChatGptReviewValidator\Model\Categories
     */
    private $gptCategories;

    /**
     * @var \Magento\Review\Model\Review
     */
    private $currentReview;

    /**
     * @param \Magento\Framework\Registry $registry
     */
    public function __construct(
        \Magento\Framework\Registry $registry,
        \DanielNavarro\ChatGptReviewValidator\Model\Source\Review\Status $gptStatus,
        \DanielNavarro\ChatGptReviewValidator\Model\Source\Review\Result $gptResult,
        \DanielNavarro\ChatGptReviewValidator\Model\Categories $gptCategories
    ) {
        $this->registry = $registry;
        $this->gptStatus = $gptStatus;
        $this->gptResult = $gptResult;
        $this->gptCategories = $gptCategories;
    }

    /**
     * @param \Magento\Review\Block\Adminhtml\Edit\Form $subject
     * @param $form
     * @return array
     */
    public function beforeSetForm(\Magento\Review\Block\Adminhtml\Edit\Form $subject, $form) {

        // Current review
        $this->currentReview = $this->registry->registry('review_data');

        // Get the review fieldset if exist and add our custom block with GPT validation info
        $form = $form->getForm();

        // Add overall notice to the current actual status
        $statusField = $form->getElement('status_id');
        $this->addNotice($statusField);

        // If status is pending do not show anything else
        if (
            $this->currentReview->getGptStatus() !=
            \DanielNavarro\ChatGptReviewValidator\Model\Source\Review\Status::REVIEW_STATUS_PENDING
        ) {

            // New fieldset for validation data
            $fieldset = $form->addFieldset(
                'review_gpt_validation',
                ['legend' => __('Open AI validation details'), 'class' => 'fieldset-wide']
            );

            // Result of the validation
            $this->addGptResult($fieldset);

            // Only show problems and scores if processed
            if (
                $this->currentReview->getGptResult() !=
                \DanielNavarro\ChatGptReviewValidator\Model\Source\Review\Result::REVIEW_RESULT_PENDING
            ) {

                // Add validation date
                $this->addGptValidationDate($fieldset);

                // Add problems
                $this->addGptProblems($fieldset);

                // New fieldset for validation scores
                $fieldset = $form->addFieldset(
                    'review_gpt_scores',
                    ['legend' => __('Result scores per category'), 'class' => 'fieldset-wide']
                );

                // Add scores
                $this->addGptScores($fieldset);
            }
        }

        return [$form];
    }

    private function addNotice($statusField) {

        // Append result to the status field
        if (
            $this->currentReview->getGptStatus() ==
            \DanielNavarro\ChatGptReviewValidator\Model\Source\Review\Status::REVIEW_STATUS_PROCESSED
        ) {
            $text = '&nbsp;&nbsp;<small>(' .
                __('Automatic validation done at') . ' ' .
                $this->currentReview->getGptValidatedAt() .
                ').&nbsp;<a href="#detail">' . __('See details') . '</a>.</small>';
            $statusField->setAfterElementHtml($text);
        }
        else if (
            $this->currentReview->getStatusId() !=
            \Magento\Review\Model\Review::STATUS_PENDING
        ) {
            $text = '&nbsp;&nbsp;<small>(' . __('This review was manually validated') . ')</small>';
            $statusField->setAfterElementHtml($text);
        }
        else {
            $text = '&nbsp;&nbsp;<small>(' . __('Awaiting for automatic validation') . ')</small>';
            $statusField->setAfterElementHtml($text);
        }
    }

    /**
     * @param $fieldset
     * @return void
     */
    private function addGptStatus($fieldset) {

        // Get status label
        $statusText = $this->gptStatus->getLabel($this->currentReview->getGptStatus());

        // Add field
        $fieldset->addField(
            'gpt_status',
            'note',
            [
                'label' => __('Validation status'),
                'text' => $statusText
            ]
        );
    }

    /**
     * @param $fieldset
     * @return void
     */
    private function addGptResult($fieldset) {

        // Get status label
        $resultText = $this->gptResult->getLabel($this->currentReview->getGptResult());
        if (
            $this->currentReview->getGptResult() ==
            \DanielNavarro\ChatGptReviewValidator\Model\Source\Review\Result::REVIEW_RESULT_FLAGGED
        ) {
            $resultText = '<span style="color: red;">' . $resultText . '</span>';
        }

        // Add field
        $fieldset->addField(
            'gpt_result',
            'note',
            [
                'label' => __('Validation result'),
                'text' => $resultText
            ]
        );
    }

    /**
     * @param $fieldset
     * @return void
     */
    private function addGptValidationDate($fieldset) {

        // Get status label
        $validationDate = $this->currentReview->getGptValidatedAt();

        // Add field
        $fieldset->addField(
            'gpt_validation_date',
            'note',
            [
                'label' => __('Validation date'),
                'text' => $validationDate
            ]
        );
    }

    /**
     * @param $fieldset
     * @return void
     */
    private function addGptProblems($fieldset) {

        // Get formated problems or none
        $problems = $this->currentReview->getGptProblems();
        if (!empty($problems)) {
            $problems = explode(',', $problems);
            $problems = array_map('trim', $problems);
            $problems = array_map('ucfirst', $problems);
            $problems = implode(', ', $problems);
            $problems = '<span style="color:red;">' . $problems . '</span>';
        }
        else {
            $problems = __('None');
        }

        $fieldset->addField(
            'problems',
            'note',
            [
                'label' => __('Problems found'),
                'text' => $problems
            ]
        );
    }

    /**
     * @param $fieldset
     * @return void
     */
    private function addGptScores($fieldset) {

        // Get scores (json)
        $scores = $this->currentReview->getGptScoreSummary();
        $scores = \json_decode($scores, true);
        if ($scores == null) {
            return;
        }

        // Extract categories
        $categoryScores = $scores['categories'] ?? [];

        // Iterate and add categories
        foreach ($categoryScores as $categoryScore => $data) {

            // Title and formated value
            $title = $this->gptCategories->getLabel($categoryScore);
            $score = $data['score'];
            $maxScore = $data['maxScore'];
            $flagged = ((double)$score > (double)$maxScore);
            if ($flagged == '1') {
                $formatedValue = '<span style="color: red;">' . number_format($score, 2) . '</span> / ' . $maxScore;
            }
            else {
                $formatedValue = number_format($score, 2) . ' / ' . $maxScore;
            }

            // Add Field
            $fieldset->addField(
                $categoryScore,
                'note',
                [
                    'label' => $title,
                    'text' => $formatedValue
                ]
            );
        }
    }
}

