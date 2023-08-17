<?php

namespace Bydn\ChatGptReviewValidator\Observer\Review;

use Bydn\ChatGptReviewValidator\Api\Data\ReviewInterface;

class LoadAfter implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \Bydn\ChatGptReviewValidator\Model\ResourceModel\Review
     */
    private $reviewExtraInfoResource;
    /**
     * @var \Bydn\ChatGptReviewValidator\Model\ReviewFactory
     */
    private $reviewExtraInfoFactory;

    /**
     * @param \Bydn\ChatGptReviewValidator\Model\ResourceModel\Review $reviewExtraInfoResource
     * @param \Bydn\ChatGptReviewValidator\Model\ReviewFactory $reviewExtraInfoFactory
     */
    public function __construct(
        \Bydn\ChatGptReviewValidator\Model\ResourceModel\Review $reviewExtraInfoResource,
        \Bydn\ChatGptReviewValidator\Model\ReviewFactory $reviewExtraInfoFactory
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
        $this->reviewExtraInfoResource->load($reviewExtraInfo, $review->getId(), 'gpt_review_id');
        $data = $reviewExtraInfo->getData();

        // If emtpy, means there is no extra data save, so we need to init them
        if (empty($data[ReviewInterface::GPT_REVIEW_ID])) {
            $data[ReviewInterface::GPT_REVIEW_ID] = $review->getId();
            $data[ReviewInterface::GPT_STATUS] =
                \Bydn\ChatGptReviewValidator\Model\Source\Review\Status::REVIEW_STATUS_PENDING;
            $data[ReviewInterface::GPT_RESULT] =
                \Bydn\ChatGptReviewValidator\Model\Source\Review\Result::REVIEW_RESULT_PENDING;
            $data[ReviewInterface::GPT_VALIDATED_AT] = null;
            $data[ReviewInterface::GPT_PROBLEMS] = '';
            $data[ReviewInterface::GPT_SCORE_SUMMARY] = '';
            $data[ReviewInterface::GPT_EXCLUDED_FOR_TRAINING] = 0;
        }

        // Remove IDs to not override the review data, which will not be the same
        unset($data['id']);

        // Add data to the review base model
        $review->addData($data);
    }
}
