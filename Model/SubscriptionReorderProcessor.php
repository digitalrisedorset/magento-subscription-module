<?php

namespace Drd\Subscribe\Model;

use Magento\Sales\Model\ResourceModel\Order\CollectionFactory as OrderCollectionFactory;
use Psr\Log\LoggerInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;

class SubscriptionReorderProcessor
{
    private OrderRepositoryInterface $orderRepository;
    private SearchCriteriaBuilder $searchCriteriaBuilder;
    private ReorderServiceProcessor $reorderService;
    private OrderItemOptionsExtractor $optionsExtractor;
    private LoggerInterface $logger;

    public function __construct(
        OrderRepositoryInterface    $orderRepository,
        SearchCriteriaBuilder       $searchCriteriaBuilder,
        ReorderServiceProcessor     $reorderService,
        OrderItemOptionsExtractor   $optionsExtractor,
        LoggerInterface             $logger
    ) {
        $this->orderRepository = $orderRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->reorderService = $reorderService;
        $this->optionsExtractor = $optionsExtractor;
        $this->logger = $logger;
    }

    public function process(): void
    {
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('status', ['complete', 'processing'], 'in')
            //->addFilter('customer_id', true, 'notnull') // ✅ Exclude guest orders
            ->addFilter('customer_id', 4) // ✅ Exclude guest orders
            ->setPageSize(100)
            ->create();

        $orders = $this->orderRepository->getList($searchCriteria)->getItems();

        foreach ($orders as $order) {
            if (!$this->orderHasSubscription($order)) {
                continue;
            }

            try {
                $this->logger->info('Reordering subscription for order #' . $order->getIncrementId());
                foreach ($order->getItems() as $item) {
                    $subscription = $item->getExtensionAttributes()->getSubscription();
                    if ($subscription) {
                        $this->reorderService->reorder($subscription, $order);
                    }
                }
            } catch (\Exception $e) {
                $this->logger->error('Failed reorder for order #' . $order->getIncrementId() . ': ' . $e->getMessage());
            }
        }
    }

    private function orderHasSubscription(\Magento\Sales\Model\Order $order): bool
    {
        foreach ($order->getAllItems() as $item) {
            if ($this->optionsExtractor->isItemSubscribed($item)) {
                return true;
            }
        }
        return false;
    }
}
