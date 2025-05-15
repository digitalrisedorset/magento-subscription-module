<?php
/**
 * Copyright © Digital Rise Dorset. All rights reserved.YING.txt for license details.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);


namespace Drd\Subscribe\Model;

use Drd\Subscribe\Api\Data\SubscriptionInterface;
use Drd\Subscribe\Api\PaymentTransactionHandlerInterface;
use Drd\Subscribe\Model\ReorderServiceProcessor\OrderBuyRequestBuilder;
use Drd\Subscribe\Model\ReorderServiceProcessor\PaymentHandler;
use Drd\Subscribe\Model\ReorderServiceProcessor\ShippingHandler;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Sales\Model\OrderFactory;
use Magento\Sales\Model\Order;
use Magento\Quote\Model\QuoteFactory;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Quote\Api\CartManagementInterface;
use Magento\Quote\Api\CartRepositoryInterface;
use Psr\Log\LoggerInterface;

class ReorderServiceProcessor
{

    /**
     * @param OrderFactory $orderFactory
     * @param QuoteFactory $quoteFactory
     * @param CartManagementInterface $cartManagement
     * @param CartRepositoryInterface $cartRepository
     * @param CustomerRepositoryInterface $customerRepository
     * @param ProductRepositoryInterface $productRepository
     * @param OrderBuyRequestBuilder $orderBuyRequestBuilder
     * @param ShippingHandler $shippingHandler
     * @param PaymentHandler $paymentHandler
     * @param PaymentTransactionHandlerInterface $transactionHandler
     * @param LoggerInterface $logger
     */
    public function __construct(
        private OrderFactory                $orderFactory,
        private QuoteFactory                $quoteFactory,
        private CartManagementInterface     $cartManagement,
        private CartRepositoryInterface     $cartRepository,
        private CustomerRepositoryInterface $customerRepository,
        private ProductRepositoryInterface  $productRepository,
        private OrderBuyRequestBuilder      $orderBuyRequestBuilder,
        private ShippingHandler             $shippingHandler,
        private PaymentHandler              $paymentHandler,
        private PaymentTransactionHandlerInterface   $transactionHandler,
        private LoggerInterface             $logger
    ) {
    }

    /**
     * @param SubscriptionInterface $subscription
     * @param Order $order
     * @return int|null
     */
    public function reorder(SubscriptionInterface $subscription, Order $order): ?int
    {
        try {
            $customerId = $order->getCustomerId();
            if (!$customerId) {
                $this->logger->warning("Skipping guest order: " . $order->getIncrementId());
                return null;
            }

            $customer = $this->customerRepository->getById($customerId);
            $quote = $this->quoteFactory->create();
            $quote->setStoreId($order->getStoreId());
            $quote->assignCustomer($customer);

            foreach ($order->getAllItems() as $orderItem) {
                if (!$orderItem->getProductId() || $subscription->getSku() != $orderItem->getSku()) {
                    continue;
                }

                $buyRequest = $this->orderBuyRequestBuilder->getBuyRequestWithProductOptions($orderItem);
                if ($buyRequest === null) continue;

                $product = $this->productRepository->get($orderItem->getSku(), false, $order->getStoreId());

                $quote->addProduct($product, $buyRequest);
            }

            $this->shippingHandler->assignShippingToQuote($order, $quote);
            $transactionId = $this->transactionHandler->processTransaction($subscription, $order);
            $this->paymentHandler->assignPaymentToQuote($quote, $transactionId);
            $quote->setData('subscription_parent_order_id', $order->getEntityId());

            $quote->collectTotals();
            $this->cartRepository->save($quote);

            $orderId = $this->cartManagement->placeOrder($quote->getId());

            $this->logger->info("Successfully reordered: {$order->getIncrementId()} → New Order #{$orderId}");
            return $orderId;
        } catch (\Exception $e) {
            $this->logger->error("Reorder failed: " . $e->getMessage());
            return null;
        }
    }
}
