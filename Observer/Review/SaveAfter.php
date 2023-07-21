<?php

namespace DanielNavarro\ChatGptReviewValidator\Observer\Review;

use Magento\Framework\Event\ObserverInterface;

class SaveAfter implements ObserverInterface
{
    protected $customProductDataFactory;

    public function __construct(

    ) {

    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $product = $observer->getEvent()->getProduct();
        $additionalData = $product->getCustomData();  // assume getCustomData() returns additional data

        $customDataModel = $this->customProductDataFactory->create();
        $customDataModel->load($product->getId(), 'product_id');
        $customDataModel->addData($additionalData);
        $customDataModel->save();
    }
}
