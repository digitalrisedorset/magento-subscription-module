<?php

namespace Drd\Subscribe\Block\Account;

use Magento\Customer\Helper\Session\CurrentCustomer;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\SortOrderBuilder;

class Subscriptions extends Template
{
    private \Magento\Framework\App\Http\Context $httpContext;
    private OrderCollectionFactory $orderCollectionFactory;
    private CurrentCustomer $currentCustomer;
    private OrderRepositoryInterface $orderRepository;
    private SearchCriteriaBuilder $searchCriteriaBuilder;

    private SortOrderBuilder $sortOrderBuilder;

    /**
     * @param Context $context
     * @param CurrentCustomer $currentCustomer
     * @param OrderRepositoryInterface $orderRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        CurrentCustomer $currentCustomer,
        OrderRepositoryInterface $orderRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        SortOrderBuilder $sortOrderBuilder,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->currentCustomer = $currentCustomer;
        $this->orderRepository = $orderRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->sortOrderBuilder = $sortOrderBuilder;
    }

    public function getCustomerId()
    {
        return $this->httpContext->getValue('customer_id');
    }

    public function getSubscriptionOrders()
    {
        $customerId = $this->currentCustomer->getCustomerId();

        $sortOrder = $this->sortOrderBuilder
            ->setField('created_at')
            ->setDirection('DESC')
            ->create();

        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('customer_id', $customerId)
            ->addFilter('status', ['complete', 'processing'], 'in')
            ->addSortOrder($sortOrder)
            ->create();

        $orderList = $this->orderRepository->getList($searchCriteria);
        $orders = $orderList->getItems();

        return $orders;
    }

}
