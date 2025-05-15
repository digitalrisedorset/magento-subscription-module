<?php
/**
 * Copyright © Digital Rise Dorset. All rights reserved.YING.txt for license details.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);


namespace Drd\Subscribe\Model\ReorderServiceProcessor;

use Drd\Subscribe\Model\Payment\Subscribe;
use Magento\Framework\Exception\LocalizedException;
use Magento\Quote\Model\Quote;

class PaymentHandler
{
    /**
     * @param Quote $quote
     * @param string $transactionId
     * @return void
     * @throws LocalizedException
     */
    public function assignPaymentToQuote(
        Quote $quote,
        string $transactionId
    ): void {
        $originalPaymentMethod = Subscribe::RECURRING_PAYMENT_CODE; //$order->getPayment()->getMethod();
        $quotePayment = $quote->getPayment();

        $quotePayment->setMethod($originalPaymentMethod)
            ->setAdditionalInformation('last_trans_id', $transactionId)
            ->setAdditionalInformation('payment_type', 'Braintree Vault')
            ->setAdditionalInformation('metadata', ['type' => 'Recurring Payment', 'fraudulent' => false]);
    }
}
