<?php

namespace Bydn\OpenAiReviewValidator\Observer\Review;

use Bydn\OpenAiReviewValidator\Api\Data\ReviewInterface;

class SaveAfter implements \Magento\Framework\Event\ObserverInterface
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
     * Saves moderation information after the review has been saved
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        // Original review
        $review = $observer->getEvent()->getObject();

        // Load extra data
        $reviewExtraInfo = $this->reviewExtraInfoFactory->create();
        $this->reviewExtraInfoResource->load($reviewExtraInfo, $review->getId(), 'open_ai_review_id');

        // Copy review data to extra.
        // At this point always there is valid data to copy because we check this on the load event
        // The only exception is with mass actions changing status. In this case we need to init data (in the review)
        if ($review->getOpenAiReviewId() == '') {
            $review->setOpenAiReviewId($review->getId());
            $review->setOpenAiStatus(
                \Bydn\OpenAiReviewValidator\Model\Source\Review\Status::REVIEW_STATUS_PENDING
            );
            $review->setOpenAiResult(
                \Bydn\OpenAiReviewValidator\Model\Source\Review\Result::REVIEW_RESULT_PENDING
            );
            $review->setOpenAiValidatedAt(null);
            $review->setOpenAiProblems('');
            $review->setOpenAiScoreSummary('');
            $review->setOpenAiExcludedForTraining(0);
        }

        // At this point always there is valid data to copy
        $reviewExtraInfo->setOpenAiReviewId($review->getOpenAiReviewId());
        $reviewExtraInfo->setOpenAiStatus($review->getOpenAiStatus());
        $reviewExtraInfo->setOpenAiResult($review->getOpenAiResult());
        $reviewExtraInfo->setOpenAiValidatedAt($review->getOpenAiValidatedAt());
        $reviewExtraInfo->setOpenAiProblems($review->getOpenAiProblems());
        $reviewExtraInfo->setOpenAiScoreSummary($review->getOpenAiScoreSummary());
        $reviewExtraInfo->setOpenAiExcludedForTraining($review->getOpenAiExcludedForTraining());

        // If the review is being saved to pending state, it must be a manual action from the backoffice,
        // so we mark it as pending for OpenAI.
        // If another status is being set, it may be a manual action from the backoffice or auto validation
        // from the crontab task
        $reviewOldStatus = $review->getOrigData('status_id');
        $reviewNewStatus = $review->getStatusId();
        $reviewOldOpenAiStatus = $review->getOrigData(ReviewInterface::OPENAI_STATUS);
        $reviewNewOpenAiStatus = $review->getOpenAiStatus();
        if ($reviewOldStatus != $reviewNewStatus) {
            if ($reviewNewStatus == \Magento\Review\Model\Review::STATUS_PENDING) {
                // The review is changing to pending so mark it also in the OpenAI status for revalidation.
                $reviewExtraInfo->setOpenAiStatus(
                    \Bydn\OpenAiReviewValidator\Model\Source\Review\Status::REVIEW_STATUS_PENDING
                );
            } elseif ($reviewOldOpenAiStatus == $reviewNewOpenAiStatus) {
                // The OPENAI status has NOT changed, but it is modified the review status. It must be manual action.
                $reviewExtraInfo->setOpenAiStatus(
                    \Bydn\OpenAiReviewValidator\Model\Source\Review\Status::REVIEW_STATUS_MANUAL
                );
            }
        }
        //else if ($reviewOldOpenAiStatus != $reviewNewOpenAiStatus) {
            // The review status has not changed, but there is a new status from OpenAI, means the review
            // has been validated by OpenAi
            // but the auto-validation is turned off, so do nothing. The user will see the result and proceed manually
        //}

        // Save updated info
        $this->reviewExtraInfoResource->save($reviewExtraInfo);
    }
}
