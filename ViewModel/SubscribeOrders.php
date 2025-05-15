<?php
/**
 * Copyright © Digital Rise Dorset. All rights reserved.YING.txt for license details.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);


namespace Drd\Subscribe\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\App\RequestInterface;

class SubscribeOrders implements ArgumentInterface
{
    private $orderRepository;
    private $searchCriteriaBuilder;
    private $request;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        RequestInterface $request
    ) {
        $this->orderRepository = $orderRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->request = $request;
    }

    public function getOrderParentId()
    {
        $parentOrderId = $this->request->getParam('parent_order_id');
        return $parentOrderId;
    }

    public function getSubscriptionOrders()
    {
        $parentOrderId = $this->getOrderParentId();

        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('subscription_parent_order_id', $parentOrderId, 'eq')
            ->create();

        $orderList = $this->orderRepository->getList($searchCriteria)->getItems();

        return $orderList;
    }
}
