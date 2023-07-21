<?php

namespace DanielNavarro\ChatGptReviewValidator\Observer\Review;

use Magento\Framework\Event\ObserverInterface;

class LoadAfter implements ObserverInterface
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

        // Remove IDs to not override the review data, with will not be the same
        unset($data['id']);
        unset($data['gpt_review_id']);

        // Add data to the review base model
        $review->addData($data);
    }
}
