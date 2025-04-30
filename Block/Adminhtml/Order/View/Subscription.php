<?php

namespace Drd\Subscribe\Block\Adminhtml\Order\View;

use Magento\Backend\Block\Template\Context;
use Magento\Framework\Registry;
use Magento\Sales\Api\OrderRepositoryInterface;

class Subscription extends \Magento\Backend\Block\Template
{
    protected Registry $registry;
    protected OrderRepositoryInterface $orderRepository;

    public function __construct(
        Context $context,
        Registry $registry,
        OrderRepositoryInterface $orderRepository,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->registry = $registry;
        $this->orderRepository = $orderRepository;
    }

    public function getOrder(): \Magento\Sales\Model\Order
    {
        return $this->registry->registry('current_order');
    }

    public function getParentOrderId(): ?int
    {
        return $this->getOrder()->getData('subscription_parent_order_id');
    }

    public function getParentOrder(): ?\Magento\Sales\Model\Order
    {
        $id = $this->getParentOrderId();
        return $id ? $this->orderRepository->get($id) : null;
    }

    public function getParentOrderUrl(): ?string
    {
        if ($order = $this->getParentOrder()) {
            return $this->getUrl('sales/order/view', ['order_id' => $order->getId()]);
        }
        return null;
    }
}
