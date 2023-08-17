<?php

namespace Bydn\OpenAiReviewValidator\Observer\Review;

use Bydn\OpenAiReviewValidator\Api\Data\ReviewInterface;

class LoadAfter implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \Bydn\OpenAiReviewValidator\Model\ResourceModel\Review
     */
    private $reviewExtraInfoResource;
    /**
     * @var \Bydn\OpenAiReviewValidator\Model\ReviewFactory
     */
    private $reviewExtraInfoFactory;

    /**
     * @param \Bydn\OpenAiReviewValidator\Model\ResourceModel\Review $reviewExtraInfoResource
     * @param \Bydn\OpenAiReviewValidator\Model\ReviewFactory $reviewExtraInfoFactory
     */
    public function __construct(
        \Bydn\OpenAiReviewValidator\Model\ResourceModel\Review $reviewExtraInfoResource,
        \Bydn\OpenAiReviewValidator\Model\ReviewFactory $reviewExtraInfoFactory
    ) {
        $this->reviewExtraInfoResource = $reviewExtraInfoResource;
        $this->reviewExtraInfoFactory = $reviewExtraInfoFactory;
    }

    /**
     * Adds moderation information to the review after it is loaded
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        // Original review
        $review = $observer->getEvent()->getObject();

        // Load extra data
        $reviewExtraInfo = $this->reviewExtraInfoFactory->create();
        $this->reviewExtraInfoResource->load($reviewExtraInfo, $review->getId(), 'open_ai_review_id');
        $data = $reviewExtraInfo->getData();

        // If emtpy, means there is no extra data save, so we need to init them
        if (empty($data[ReviewInterface::OPENAI_REVIEW_ID])) {
            $data[ReviewInterface::OPENAI_REVIEW_ID] = $review->getId();
            $data[ReviewInterface::OPENAI_STATUS] =
                \Bydn\OpenAiReviewValidator\Model\Source\Review\Status::REVIEW_STATUS_PENDING;
            $data[ReviewInterface::OPENAI_RESULT] =
                \Bydn\OpenAiReviewValidator\Model\Source\Review\Result::REVIEW_RESULT_PENDING;
            $data[ReviewInterface::OPENAI_VALIDATED_AT] = null;
            $data[ReviewInterface::OPENAI_PROBLEMS] = '';
            $data[ReviewInterface::OPENAI_SCORE_SUMMARY] = '';
            $data[ReviewInterface::OPENAI_EXCLUDED_FOR_TRAINING] = 0;
        }

        // Remove IDs to not override the review data, which will not be the same
        unset($data['id']);

        // Add data to the review base model
        $review->addData($data);
    }
}
