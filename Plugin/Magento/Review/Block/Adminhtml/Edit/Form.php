<?php

namespace Bydn\OpenAiReviewValidator\Plugin\Magento\Review\Block\Adminhtml\Edit;

class Form
{
    /**
     * @var \Magento\Framework\Registry
     */
    private $registry;

    /**
     * @var \Bydn\OpenAiReviewValidator\Model\Source\Review\Status
     */
    private $openAiStatus;

    /**
     * @var \Bydn\OpenAiReviewValidator\Model\Source\Review\Result
     */
    private $openAiResult;

    /**
     * @var \Bydn\OpenAiReviewValidator\Model\Categories
     */
    private $openAiCategories;

    /**
     * @var \Magento\Review\Model\Review
     */
    private $currentReview;

    /**
     * @param \Magento\Framework\Registry $registry
     * @param \Bydn\OpenAiReviewValidator\Model\Source\Review\Status $openAiStatus
     * @param \Bydn\OpenAiReviewValidator\Model\Source\Review\Result $openAiResult
     * @param \Bydn\OpenAiReviewValidator\Model\Categories $openAiCategories
     */
    public function __construct(
        \Magento\Framework\Registry                            $registry,
        \Bydn\OpenAiReviewValidator\Model\Source\Review\Status $openAiStatus,
        \Bydn\OpenAiReviewValidator\Model\Source\Review\Result $openAiResult,
        \Bydn\OpenAiReviewValidator\Model\Categories $openAiCategories
    ) {
        $this->registry = $registry;
        $this->openAiStatus = $openAiStatus;
        $this->openAiResult = $openAiResult;
        $this->openAiCategories = $openAiCategories;
    }

    /**
     * Modifies the form before it is set to the form container
     *
     * @param \Magento\Review\Block\Adminhtml\Edit\Form $subject
     * @param \Magento\Framework\Data\Form $form
     * @return array
     */
    public function beforeSetForm(
        \Magento\Review\Block\Adminhtml\Edit\Form $subject,
        \Magento\Framework\Data\Form $form
    ) {
        // Current review
        $this->currentReview = $this->registry->registry('review_data');

        // Get the review fieldset if exist and add our custom block with OPENAI validation info
        $form = $form->getForm();

        // Add overall notice to the current actual status
        $statusField = $form->getElement('status_id');
        $this->addNotice($statusField);

        // If status is pending do not show anything else
        if ($this->currentReview->getOpenAiStatus() !=
            \Bydn\OpenAiReviewValidator\Model\Source\Review\Status::REVIEW_STATUS_PENDING) {

            // New fieldset for validation data
            $fieldset = $form->addFieldset(
                'review_open_ai_validation',
                ['legend' => __('OpenAI validation details'), 'class' => 'fieldset-wide']
            );

            // Result of the validation
            $this->addOpenAiResult($fieldset);

            // Only show problems and scores if processed
            if ($this->currentReview->getOpenAiResult() !=
                \Bydn\OpenAiReviewValidator\Model\Source\Review\Result::REVIEW_RESULT_PENDING) {

                // Add validation date
                $this->addOpenAiValidationDate($fieldset);

                // Add problems
                $this->addOpenAiProblems($fieldset);

                // New fieldset for validation scores
                $fieldset = $form->addFieldset(
                    'review_open_ai_scores',
                    ['legend' => __('Result scores per category'), 'class' => 'fieldset-wide']
                );

                // Add scores
                $this->addOpenAiScores($fieldset);
            }
        }

        return [$form];
    }

    /**
     * Adds notice below the status field to indicate the admin about the OPENAI validation status
     *
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $statusField
     * @return void
     */
    private function addNotice(\Magento\Framework\Data\Form\Element\AbstractElement $statusField)
    {

        // Append result to the status field
        if ($this->currentReview->getOpenAiStatus() ==
            \Bydn\OpenAiReviewValidator\Model\Source\Review\Status::REVIEW_STATUS_PROCESSED) {

            $text = '&nbsp;&nbsp;<small>(' .
                __('Automatic validation done at') . ' ' .
                $this->currentReview->getOpenAiValidatedAt() .
                ').&nbsp;<a href="#detail">' . __('See details') . '</a>.</small>';
            $statusField->setAfterElementHtml($text);

        } elseif ($this->currentReview->getStatusId() != \Magento\Review\Model\Review::STATUS_PENDING) {

            $text = '&nbsp;&nbsp;<small>(' . __('This review was manually validated') . ')</small>';
            $statusField->setAfterElementHtml($text);

        } else {

            $text = '&nbsp;&nbsp;<small>(' . __('Awaiting for automatic validation') . ')</small>';
            $statusField->setAfterElementHtml($text);

        }
    }

    /**
     * Add OPENAI status to the fieldset
     *
     * @param \Magento\Framework\Data\Form\Element\Fieldset $fieldset
     * @return void
     */
    private function addOpenAiStatus(\Magento\Framework\Data\Form\Element\Fieldset $fieldset)
    {
        // Get status label
        $statusText = $this->openAiStatus->getLabel($this->currentReview->getOpenAiStatus());

        // Add field
        $fieldset->addField(
            'open_ai_status',
            'note',
            [
                'label' => __('Validation status'),
                'text' => $statusText
            ]
        );
    }

    /**
     * Add OPENAI validation result to the fieldset
     *
     * @param \Magento\Framework\Data\Form\Element\Fieldset $fieldset
     * @return void
     */
    private function addOpenAiResult(\Magento\Framework\Data\Form\Element\Fieldset $fieldset)
    {
        // Get status label
        $resultText = $this->openAiResult->getLabel($this->currentReview->getOpenAiResult());
        if ($this->currentReview->getOpenAiResult() ==
            \Bydn\OpenAiReviewValidator\Model\Source\Review\Result::REVIEW_RESULT_FLAGGED) {
            $resultText = '<span style="color: red;">' . $resultText . '</span>';
        }

        // Add field
        $fieldset->addField(
            'open_ai_result',
            'note',
            [
                'label' => __('Validation result'),
                'text' => $resultText
            ]
        );
    }

    /**
     * Add OPENAI validation date to the fieldset
     *
     * @param \Magento\Framework\Data\Form\Element\Fieldset $fieldset
     * @return void
     */
    private function addOpenAiValidationDate(\Magento\Framework\Data\Form\Element\Fieldset $fieldset)
    {
        // Get status label
        $validationDate = $this->currentReview->getOpenAiValidatedAt();

        // Add field
        $fieldset->addField(
            'open_ai_validation_date',
            'note',
            [
                'label' => __('Validation date'),
                'text' => $validationDate
            ]
        );
    }

    /**
     * Add OPENAI problems to the fieldset
     *
     * @param \Magento\Framework\Data\Form\Element\Fieldset $fieldset
     * @return void
     */
    private function addOpenAiProblems(\Magento\Framework\Data\Form\Element\Fieldset $fieldset)
    {
        // Get formated problems or none
        $problems = $this->currentReview->getOpenAiProblems();
        if (!empty($problems)) {
            $problems = explode(',', $problems);
            $problems = array_map('trim', $problems);
            $problems = array_map('ucfirst', $problems);
            $problems = implode(', ', $problems);
            $problems = '<span style="color:red;">' . $problems . '</span>';
        } else {
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
     * Add OPENAI scores to the fieldset
     *
     * @param \Magento\Framework\Data\Form\Element\Fieldset $fieldset
     * @return void
     */
    private function addOpenAiScores(\Magento\Framework\Data\Form\Element\Fieldset $fieldset)
    {
        // Get scores (json)
        $scores = $this->currentReview->getOpenAiScoreSummary();
        $scores = \json_decode($scores, true);
        if ($scores == null) {
            return;
        }

        // Extract categories
        $categoryScores = $scores['categories'] ?? [];

        // Iterate and add categories
        foreach ($categoryScores as $categoryScore => $data) {

            // Title and formated value
            $title = $this->openAiCategories->getLabel($categoryScore);
            $score = $data['score'];
            $maxScore = $data['maxScore'];
            $flagged = ((double)$score > (double)$maxScore);
            if ($flagged == '1') {
                $formatedValue = '<span style="color: red;">' . number_format($score, 2) . '</span> / ' . $maxScore;
            } else {
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
