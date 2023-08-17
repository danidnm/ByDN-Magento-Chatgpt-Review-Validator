<?php

namespace Bydn\OpenAiReviewValidator\Preference\Magento\Review\Block\Adminhtml;

class Grid extends \Magento\Review\Block\Adminhtml\Grid
{
    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    private $resourceConnection;

    /**
     * @var \Bydn\OpenAiReviewValidator\Model\Source\Review\Status
     */
    private $openAiStatus;

    /**
     * @var \Bydn\OpenAiReviewValidator\Model\Source\Review\Result
     */
    private $openAiResult;

    /**
     * Controls if the join with OpenAi table is already done
     * @var bool
     */
    private $joinAdded = false;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \Magento\Review\Model\ReviewFactory $reviewFactory
     * @param \Magento\Review\Model\ResourceModel\Review\Product\CollectionFactory $productsFactory
     * @param \Magento\Review\Helper\Data $reviewData
     * @param \Magento\Review\Helper\Action\Pager $reviewActionPager
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Framework\App\ResourceConnection $resourceConnection
     * @param \Bydn\OpenAiReviewValidator\Model\Source\Review\Status $openAiStatus
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Magento\Review\Model\ReviewFactory $reviewFactory,
        \Magento\Review\Model\ResourceModel\Review\Product\CollectionFactory $productsFactory,
        \Magento\Review\Helper\Data $reviewData,
        \Magento\Review\Helper\Action\Pager $reviewActionPager,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        \Bydn\OpenAiReviewValidator\Model\Source\Review\Status $openAiStatus,
        \Bydn\OpenAiReviewValidator\Model\Source\Review\Result $openAiResult,
        array $data = []
    ) {
        $this->resourceConnection = $resourceConnection;
        $this->openAiStatus = $openAiStatus;
        $this->openAiResult = $openAiResult;
        parent::__construct(
            $context,
            $backendHelper,
            $reviewFactory,
            $productsFactory,
            $reviewData,
            $reviewActionPager,
            $coreRegistry,
            $data
        );
    }

    /**
     * Joins the table with moderation information
     *
     * @param \Magento\Framework\Data\Collection $collection
     * @return void
     */
    public function setCollection($collection)
    {
        if (!$this->joinAdded) {
            $openaiTable = $this->resourceConnection->getTableName('bydn_open_ai_review_scores');

            $collection->getSelect()->joinLeft(
                ['openai' => $openaiTable],
                'rt.review_id = openai.open_ai_review_id',
                ['open_ai_status', 'open_ai_result']
            );

            $this->joinAdded = true;
        }

        parent::setCollection($collection);
    }

    /**
     * Add OpenAI validation status to the grid
     *
     * @return void
     */
    protected function _prepareColumns()
    {
        // Where to add
        $after = 'status';
        if ($this->_coreRegistry->registry('usePendingFilter')) {
            $after = 'created_at';
        }

        // Add Open AI status column
        $this->addColumnAfter(
            'open_ai_status',
            [
                'header' => __('OpenAI Status'),
                'type' => 'options',
                'options' => $this->openAiStatus->toColumnOptionArray(),
                'filter_index' => 'openai.open_ai_status',
                'index' => 'open_ai_status'
            ],
            $after
        );

        // Add Open AI status column
        $this->addColumnAfter(
            'open_ai_result',
            [
                'header' => __('OpenAI Result'),
                'type' => 'options',
                'options' => $this->openAiResult->toColumnOptionArray(),
                'filter_index' => 'openai.open_ai_result',
                'index' => 'open_ai_result'
            ],
            'open_ai_status'
        );

        parent::_prepareColumns();
    }
}
