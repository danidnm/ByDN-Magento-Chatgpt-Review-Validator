<?php

namespace Bydn\OpenAiReviewValidator\Cron;

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
     * @var \Bydn\OpenAi\Model\OpenAi\Moderation
     */
    private $openAiModeration;

    /**
     * @var \Bydn\OpenAiReviewValidator\Helper\Config
     */
    private $openAiReviewValidationConfig;

    /**
     * @var \Bydn\OpenAiReviewValidator\Model\Validator
     */
    private $reviewValidator;

    /**
     * @param \Magento\Review\Model\ResourceModel\Review\CollectionFactory $reviewCollectionFactory
     * @param \Magento\Review\Model\ResourceModel\Review $reviewResource
     * @param \Bydn\OpenAi\Model\OpenAi\Moderation $openAiModeration
     * @param \Bydn\OpenAiReviewValidator\Helper\Config $openAiReviewValidationConfig
     * @param \Bydn\OpenAiReviewValidator\Model\Validator $reviewValidator
     */
    public function __construct(
        \Magento\Review\Model\ResourceModel\Review\CollectionFactory $reviewCollectionFactory,
        \Magento\Review\Model\ResourceModel\Review $reviewResource,
        \Bydn\OpenAi\Model\OpenAi\Moderation $openAiModeration,
        \Bydn\OpenAiReviewValidator\Helper\Config $openAiReviewValidationConfig,
        \Bydn\OpenAiReviewValidator\Model\Validator $reviewValidator
    ) {
        $this->reviewCollectionFactory = $reviewCollectionFactory;
        $this->reviewResource = $reviewResource;
        $this->openAiModeration = $openAiModeration;
        $this->openAiReviewValidationConfig = $openAiReviewValidationConfig;
        $this->reviewValidator = $reviewValidator;
    }

    /**
     * Process all the pending reviews and validates through OpenAi
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
        $collection->getSelect()->limit(50);

        return $collection;
    }
}
