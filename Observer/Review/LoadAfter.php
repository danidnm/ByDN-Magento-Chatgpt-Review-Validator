<?php

namespace DanielNavarro\ChatGptReviewValidator\Observer\Review;

use DanielNavarro\ChatGptReviewValidator\Api\Data\ReviewInterface;

class LoadAfter implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \DanielNavarro\ChatGptReviewValidator\Model\ResourceModel\Review
     */
    private $reviewExtraInfoResource;
    /**
     * @var \DanielNavarro\ChatGptReviewValidator\Model\ReviewFactory
     */
    private $reviewExtraInfoFactory;

    public function __construct(
        \DanielNavarro\ChatGptReviewValidator\Model\ResourceModel\Review $reviewExtraInfoResource,
        \DanielNavarro\ChatGptReviewValidator\Model\ReviewFactory $reviewExtraInfoFactory
    ) {
        $this->reviewExtraInfoResource = $reviewExtraInfoResource;
        $this->reviewExtraInfoFactory = $reviewExtraInfoFactory;
    }

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
            $data[ReviewInterface::GPT_STATUS] = \DanielNavarro\ChatGptReviewValidator\Model\Source\Review\Status::REVIEW_STATUS_PENDING;
            $data[ReviewInterface::GPT_RESULT] = \DanielNavarro\ChatGptReviewValidator\Model\Source\Review\Result::REVIEW_RESULT_PENDING;
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
