<?php

namespace DanielNavarro\ChatGptReviewValidator\Cron;

use Magento\Review\Model\ResourceModel\Review\Collection;

class Validate
{
    /**
     * @var \Magento\Review\Model\ResourceModel\Review\CollectionFactory
     */
    private $reviewCollectionFactory;

    /**
     * @var \Magento\Review\Model\ResourceModel\Review
     */
    private $reviewResource;

    /**
     * @var \DanielNavarro\ChatGpt\Model\ChatGpt\Moderation
     */
    private $chatGptModeration;

    /**
     * @var \DanielNavarro\ChatGptReviewValidator\Helper\Config
     */
    private $chatGptReviewValidationConfig;

    /**
     * @var \DanielNavarro\ChatGptReviewValidator\Model\Validator
     */
    private $reviewValidator;

    /**
     * @param \Magento\Review\Model\ResourceModel\Review\CollectionFactory $reviewCollectionFactory
     * @param \Magento\Review\Model\ResourceModel\Review $reviewResource
     * @param \DanielNavarro\ChatGpt\Model\ChatGpt\Moderation $chatGptModeration
     * @param \DanielNavarro\ChatGptReviewValidator\Helper\Config $chatGptReviewValidationConfig
     * @param \DanielNavarro\ChatGptReviewValidator\Model\Validator $reviewValidator
     */
    public function __construct(
        \Magento\Review\Model\ResourceModel\Review\CollectionFactory $reviewCollectionFactory,
        \Magento\Review\Model\ResourceModel\Review $reviewResource,
        \DanielNavarro\ChatGpt\Model\ChatGpt\Moderation $chatGptModeration,
        \DanielNavarro\ChatGptReviewValidator\Helper\Config $chatGptReviewValidationConfig,
        \DanielNavarro\ChatGptReviewValidator\Model\Validator $reviewValidator
    ) {
        $this->reviewCollectionFactory = $reviewCollectionFactory;
        $this->reviewResource = $reviewResource;
        $this->chatGptModeration = $chatGptModeration;
        $this->chatGptReviewValidationConfig = $chatGptReviewValidationConfig;
        $this->reviewValidator = $reviewValidator;
    }

    /**
     * Process all the pending reviews and validates through ChatGpt
     *
     * @return void
     */
    public function process()
    {
        // Get the collection of pending reviews
        $pendingReviews = $this->getPendingReviews();

        // Iterate and validate
        foreach ($pendingReviews as $review) {

            // Load full review
            $this->reviewResource->load($review, $review->getReviewId());

            // Validate
            list($processed, $review) = $this->reviewValidator->validateReview($review);

            // Save new data if needed
            if ($processed) {
                $this->reviewResource->save($review);
            }
        }
    }

    /**
     * Returns a collection of pending reviews
     *
     * @return \Magento\Review\Model\ResourceModel\Review\Collection
     */
    private function getPendingReviews()
    {
        // Get collection of pending reviews
        $collection = $this->reviewCollectionFactory->create();
        $collection->addFieldToSelect('review_id');
        $collection->addFieldToFilter('status_id', \Magento\Review\Model\Review::STATUS_PENDING);

        return $collection;
    }
}
