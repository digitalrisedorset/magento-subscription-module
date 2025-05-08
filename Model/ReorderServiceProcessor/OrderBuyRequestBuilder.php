<?php

namespace Drd\Subscribe\Model\ReorderServiceProcessor;

use Magento\Framework\DataObject;
use Psr\Log\LoggerInterface;

class OrderBuyRequestBuilder
{
    public function __construct(
        private LoggerInterface             $logger
    ) {
    }

    /**
     * @param $orderItem
     * @return DataObject|null
     */
    public function getBuyRequestWithProductOptions($orderItem)
    {
        $buyRequestData = $orderItem->getProductOptions()['info_buyRequest'] ?? null;
        if (!$buyRequestData) {
            $this->logger->warning("Missing buyRequest for item SKU {$orderItem->getSku()} on order #{$order->getIncrementId()}");
            return null;
        }
        $buyRequest = new \Magento\Framework\DataObject($buyRequestData);

        return $buyRequest;
    }
}
