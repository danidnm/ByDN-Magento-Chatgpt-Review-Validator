<?php

namespace DanielNavarro\ChatGptReviewValidator\Observer\Review;

use Magento\Framework\Event\ObserverInterface;

class SaveAfter implements ObserverInterface
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

        // Copy data from base review to extra info entity (if new, set some default data)
        if ($reviewExtraInfo->getGptReviewId() == '') {
            $reviewExtraInfo->setGptReviewId($review->getId());
            $reviewExtraInfo->setGptStatus(\DanielNavarro\ChatGptReviewValidator\Model\Source\Review\Status::REVIEW_STATUS_PENDING);
            $reviewExtraInfo->setGptResult(\DanielNavarro\ChatGptReviewValidator\Model\Source\Review\Result::REVIEW_RESULT_PENDING);
            $reviewExtraInfo->setGptExcludedForTraining(0);
        }
        else {
            $reviewExtraInfo->setGptStatus($review->getGptStatus());
            $reviewExtraInfo->setGptValidatedAt($review->getGptValidatedAt());
            $reviewExtraInfo->setGptResult($review->getGptResult());
            $reviewExtraInfo->setGptProblems($review->getGptProblems());
            $reviewExtraInfo->setGptExcludedForTraining($review->getGptExcludedForTraining());
        }

        // Save updated info
        $this->reviewExtraInfoResource->save($reviewExtraInfo);
    }
}
