<?php
/**
 * Copyright © Digital Rise Dorset. All rights reserved.YING.txt for license details.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);


namespace Drd\Subscribe\Model;

use Drd\Subscribe\Model\ReorderServiceProcessor\OrderFinder;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Psr\Log\LoggerInterface;

class SubscriptionReorderProcessor
{
    private ReorderServiceProcessor $reorderService;
    private LoggerInterface $logger;
    private SubscriptionRepository $subscriptionRepository;
    private ReorderServiceProcessor\OrderFinder $orderFinder;

    /**
     * @param SubscriptionRepository $subscriptionRepository
     * @param ReorderServiceProcessor $reorderService
     * @param OrderFinder $orderFinder
     * @param LoggerInterface $logger
     */
    public function __construct(
        SubscriptionRepository    $subscriptionRepository,
        ReorderServiceProcessor   $reorderService,
        OrderFinder $orderFinder,
        LoggerInterface           $logger
    ) {
        $this->reorderService = $reorderService;
        $this->logger = $logger;
        $this->subscriptionRepository = $subscriptionRepository;
        $this->orderFinder = $orderFinder;
    }

    public function process(): void
    {
        $dueSubscriptions = $this->subscriptionRepository->getDueSubscriptions();

        $ordersById = $this->orderFinder->getValidOriginOrders($dueSubscriptions);

        foreach ($dueSubscriptions as $subscription) {
            $orderId = $subscription->getOriginalOrderId();

            if (!isset($ordersById[$orderId])) {
                $this->logger->error("Missing original order #$orderId for subscription #{$subscription->getId()}");
                continue;
            }

            try {
                $order = $ordersById[$orderId];
                $this->logger->info('Reordering subscription for order #' . $order->getIncrementId());
                $this->reorderService->reorder($subscription, $order);
            } catch (\Exception $e) {
                $this->logger->error('Failed reorder for order #' . $order->getIncrementId() . ': ' . $e->getMessage());
            }
        }
    }
}
