<?php

namespace DanielNavarro\ChatGptReviewValidator\Preference\Magento\Review\Block\Adminhtml;

class Grid extends \Magento\Review\Block\Adminhtml\Grid
{
    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    private $resourceConnection;

    /**
     * @var \DanielNavarro\ChatGptReviewValidator\Model\Source\Review\Status
     */
    private $gptStatus;

    /**
     * Controls if the join with open ai table is already done
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
        \DanielNavarro\ChatGptReviewValidator\Model\Source\Review\Status $gptStatus,
        array $data = []
    ) {
        $this->resourceConnection = $resourceConnection;
        $this->gptStatus = $gptStatus;
        parent::__construct(
            $context,
            $backendHelper,
            $reviewFactory,
            $productsFactory,
            $reviewData,
            $reviewActionPager,
            $coreRegistry,
            $data);
    }

    /**
     * @param $collection
     * @return void
     */
    public function setCollection($collection)
    {
        if (!$this->joinAdded) {
            $gptTable = $this->resourceConnection->getTableName('dn_chatgpt_review_scores');

            $collection->getSelect()->joinLeft(
                ['gpt' => $gptTable],
                'rt.review_id = gpt.gpt_review_id',
                ['gpt_status']
            );

            $this->joinAdded = true;
        }

        parent::setCollection($collection);
    }

    protected function _prepareColumns()
    {
        parent::_prepareColumns();

        // Where to add
        $after = 'status';
        if ($this->_coreRegistry->registry('usePendingFilter')) {
            $after = 'created_at';
        }

        // Add Open AI status column
        $this->addColumnAfter(
            'gpt_status',
            [
                'header' => __('OpenAI Validation'),
                'type' => 'options',
                'options' => $this->gptStatus->toColumnOptionArray(),
                'filter_index' => 'gpt.gpt_status',
                'index' => 'gpt_status'
            ],
            $after
        );
    }
}
