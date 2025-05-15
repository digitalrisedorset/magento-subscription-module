<?php
/**
 * Copyright © Digital Rise Dorset. All rights reserved.YING.txt for license details.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Drd\Subscribe\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Serialize\SerializerInterface;

class SubscriptionHandlerObserver implements ObserverInterface
{
    protected SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function execute(Observer $observer)
    {
        /** @var \Magento\Quote\Model\Quote $quote */
        $quote = $observer->getEvent()->getQuote();

        /** @var \Magento\Sales\Model\Order $order */
        $order = $observer->getEvent()->getOrder();

        if ($quote->getData('subscription_parent_order_id')) {
            $order->setData('subscription_parent_order_id', $quote->getData('subscription_parent_order_id'));
        }

        foreach ($order->getAllItems() as $orderItem) {
            if ($order->getData('subscription_parent_order_id')) {
                // Skip adding custom options
                continue;
            }

            $quoteItem = $quote->getItemById($orderItem->getQuoteItemId());

            if (!$quoteItem) {
                continue;
            }

            $buyRequestOption = $quoteItem->getOptionByCode('info_buyRequest');

            if (!$buyRequestOption) {
                continue;
            }

            try {
                $buyRequest = $this->serializer->unserialize($buyRequestOption->getValue());
            } catch (\Exception $e) {
                continue;
            }

            if (!isset($buyRequest['options'])) {
                continue;
            }

            $options = $buyRequest['options'];

            $additionalOptions = [];

            if (!empty($options['subscription'])) {
                $additionalOptions[] = [
                    'label' => __('Subscription'),
                    'value' => __('Yes')
                ];
            }

            if (!empty($options['recurrence'])) {
                $additionalOptions[] = [
                    'label' => __('Recurrence'),
                    'value' => ucfirst($options['recurrence'])
                ];
            }

            if (!empty($additionalOptions)) {
                $existingOptions = $orderItem->getProductOptionByCode('additional_options') ?? [];
                $mergedOptions = array_merge($existingOptions, $additionalOptions);

                $orderItem->addOption([
                    'code' => 'additional_options',
                    'value' => $this->serializer->serialize($mergedOptions)
                ]);
            }
        }
    }
}
