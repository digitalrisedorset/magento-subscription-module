<?php

namespace Drd\Subscribe\Observer;

use Drd\Subscribe\Model\OrderItemOptionsExtractor;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Serialize\SerializerInterface;
use Drd\Subscribe\Model\SubscriptionFactory;
use Psr\Log\LoggerInterface;

class CreateSubscriptionFromOrderObserver implements ObserverInterface
{
    private SubscriptionFactory $subscriptionFactory;
    private SerializerInterface $serializer;
    private LoggerInterface $logger;
    private OrderItemOptionsExtractor $orderItemOptionsExtractor;

    /**
     * @param SubscriptionFactory $subscriptionFactory
     * @param SerializerInterface $serializer
     * @param OrderItemOptionsExtractor $orderItemOptionsExtractor
     * @param LoggerInterface $logger
     */
    public function __construct(
        SubscriptionFactory                            $subscriptionFactory,
        SerializerInterface                            $serializer,
        OrderItemOptionsExtractor $orderItemOptionsExtractor,
        LoggerInterface                                $logger
    ) {
        $this->subscriptionFactory = $subscriptionFactory;
        $this->serializer = $serializer;
        $this->logger = $logger;
        $this->orderItemOptionsExtractor = $orderItemOptionsExtractor;
    }

    public function execute(Observer $observer)
    {
        /** @var \Magento\Sales\Model\Order $order */
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
                $subscription = $this->subscriptionFactory->create();
                $subscription->setData([
                    'order_id' => $order->getId(),
                    'order_item_id' => $orderItem->getId(),
                    'sku' => $sku,
                    'recurrence' => $optionSubscription['recurrence'],
                ]);
                $subscription->save();
            } catch (\Throwable $e) {
                $this->logger->error('[Subscription] Failed to create for order ' . $order->getIncrementId() . ': ' . $e->getMessage());
            }
        }
    }

    private function findOrderItemBySku(\Magento\Sales\Model\Order $order, string $sku): ?\Magento\Sales\Model\Order\Item
    {
        foreach ($order->getAllVisibleItems() as $item) {
            if ($item->getSku() === $sku) {
                return $item;
            }
        }
        return null;
    }

}
