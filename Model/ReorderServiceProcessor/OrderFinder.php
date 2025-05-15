<?php
/**
 * Copyright © Digital Rise Dorset. All rights reserved.YING.txt for license details.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);


namespace Drd\Subscribe\Model\ReorderServiceProcessor;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\OrderRepositoryInterface;

class OrderFinder
{
    private OrderRepositoryInterface $orderRepository;
    private SearchCriteriaBuilder $searchCriteriaBuilder;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->orderRepository = $orderRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }


    /**
     * Loads only orders in 'processing' or 'complete' state that match the given subscriptions.
     * Any order not in this state will be excluded (e.g. canceled, pending, etc).
     *
     * @param array $dueSubscriptions
     * @return array
     */
    public function getValidOriginOrders(array $dueSubscriptions): array
    {
        $orderIds = array_filter(array_map(
            fn($sub) => $sub->getOriginalOrderId(),
            $dueSubscriptions
        ));

        if (empty($orderIds)) {
            return [];
        }

        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('entity_id', $orderIds, 'in')
            ->addFilter('status', ['complete', 'processing'], 'in')
            ->setPageSize(100)
            ->create();

        $orders = $this->orderRepository->getList($searchCriteria)->getItems();

        $ordersById = [];
        foreach ($orders as $order) {
            $ordersById[$order->getEntityId()] = $order;
        }

        return $ordersById;
    }
}

