<?php

namespace Drd\Subscribe\Observer;

use Drd\Subscribe\Model\OrderItemOptionsExtractor;
use Drd\Subscribe\Model\ProductSubscription\SubscriptionPersister;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Item;
use Psr\Log\LoggerInterface;

class CreateSubscriptionFromOrderObserver implements ObserverInterface
{
    private SerializerInterface $serializer;
    private LoggerInterface $logger;
    private OrderItemOptionsExtractor $orderItemOptionsExtractor;
    private SubscriptionPersister $subscriptionPersister;

    /**
     * @param SerializerInterface $serializer
     * @param OrderItemOptionsExtractor $orderItemOptionsExtractor
     * @param SubscriptionPersister $subscriptionPersister
     * @param LoggerInterface $logger
     */
    public function __construct(
        SerializerInterface       $serializer,
        OrderItemOptionsExtractor $orderItemOptionsExtractor,
        SubscriptionPersister     $subscriptionPersister,
        LoggerInterface           $logger
    ) {
        $this->serializer = $serializer;
        $this->logger = $logger;
        $this->orderItemOptionsExtractor = $orderItemOptionsExtractor;
        $this->subscriptionPersister = $subscriptionPersister;
    }

    public function execute(Observer $observer)
    {
        /** @var Order $order */
        $order = $observer->getEvent()->getOrder();

        foreach ($order->getAllVisibleItems() as $orderItem) {
            $optionSubscription = $this->orderItemOptionsExtractor->getOrderItemCustomOption($orderItem);

            if ($optionSubscription === null) {
                continue;
            }

            $sku = $orderItem->getSku();
            $orderItem = $this->findOrderItemBySku($order, $sku);

            if (!$orderItem) {
                $this->logger->warning('[Subscription] No matching order item for SKU: ' . $sku);
                continue;
            }

            try {
                $this->subscriptionPersister->createProductSubscription($order, $orderItem, $optionSubscription);
            } catch (\Throwable $e) {
                $this->logger->error('[Subscription] Failed to create for order ' . $order->getIncrementId() . ': ' . $e->getMessage());
            }
        }
    }

    /**
     * @param Order $order
     * @param string $sku
     * @return Item|null
     */
    private function findOrderItemBySku(Order $order, string $sku): ?\Magento\Sales\Model\Order\Item
    {
        foreach ($order->getAllVisibleItems() as $item) {
            if ($item->getSku() === $sku) {
                return $item;
            }
        }
        return null;
    }

}
