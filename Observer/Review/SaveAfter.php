<?php

namespace DanielNavarro\ChatGptReviewValidator\Observer\Review;

use DanielNavarro\ChatGptReviewValidator\Api\Data\ReviewInterface;

class SaveAfter implements \Magento\Framework\Event\ObserverInterface
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

        // Copy review data to extra.
        // At this point always there is valid data to copy because we check this on the load event
        // The only exception is with mass actions changing status. In this case we need to init data (in the review)
        if ($review->getGptReviewId() == '') {
            $review->setGptReviewId($review->getId());
            $review->setGptStatus(\DanielNavarro\ChatGptReviewValidator\Model\Source\Review\Status::REVIEW_STATUS_PENDING);
            $review->setGptResult(\DanielNavarro\ChatGptReviewValidator\Model\Source\Review\Result::REVIEW_RESULT_PENDING);
            $review->setGptValidatedAt(null);
            $review->setGptProblems('');
            $review->setGptExcludedForTraining(0);
        }

        // At this point always there is valid data to copy
        $reviewExtraInfo->setGptReviewId($review->getGptReviewId());
        $reviewExtraInfo->setGptStatus($review->getGptStatus());
        $reviewExtraInfo->setGptResult($review->getGptResult());
        $reviewExtraInfo->setGptValidatedAt($review->getGptValidatedAt());
        $reviewExtraInfo->setGptProblems($review->getGptProblems());
        $reviewExtraInfo->setGptExcludedForTraining($review->getGptExcludedForTraining());

        // If the review is being saved to pending state, it must be a manual action from the backoffice, so we mark it as pending for Open AI.
        // If another status is being set, it may be a manual action from the backoffice or auto validation from the crontab task
        $reviewOldStatus = $review->getOrigData('status_id');
        $reviewNewStatus = $review->getStatusId();
        $reviewOldGptStatus = $review->getOrigData(ReviewInterface::GPT_STATUS);
        $reviewNewGptStatus = $review->getGptStatus();
        if ($reviewOldStatus != $reviewNewStatus) {
            if ($reviewNewStatus == \Magento\Review\Model\Review::STATUS_PENDING) {
                // The review is changing to pending so mark it also in the gpt status for revalidation.
                $reviewExtraInfo->setGptStatus(\DanielNavarro\ChatGptReviewValidator\Model\Source\Review\Status::REVIEW_STATUS_PENDING);
            }
            else if ($reviewOldGptStatus == $reviewNewGptStatus) {
                // The GPT status has NOT changed, but it is modified the review status. It must be manual action.
                $reviewExtraInfo->setGptStatus(\DanielNavarro\ChatGptReviewValidator\Model\Source\Review\Status::REVIEW_STATUS_MANUAL);
            }
        }
        else if ($reviewOldGptStatus != $reviewNewGptStatus) {
            // The review status has not changed, but there is a new status from chat gpt. Means the review has been validated by Chat GPT
            // but the auto-validation is turned off, so do nothing. The user will see the result and proceed manually
        }

        // Save updated info
        $this->reviewExtraInfoResource->save($reviewExtraInfo);
    }
}
