<?php
/**
 * Copyright © Digital Rise Dorset. All rights reserved.YING.txt for license details.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);


namespace Drd\Subscribe\Model\Payment;

use Magento\Payment\Model\Method\AbstractMethod;

class Subscribe extends AbstractMethod
{
    public const RECURRING_PAYMENT_CODE = 'drd_subscribe_payment';

    /**
     * @var string
     */
    protected $_code = Subscribe::RECURRING_PAYMENT_CODE;
    protected $_isOffline = true;
    protected $_canAuthorize = true;
    protected $_canCapture = true;

    protected $_infoBlockType = \Drd\Subscribe\Block\Payment\Info\Subscribe::class;


    public function authorize(\Magento\Payment\Model\InfoInterface $payment, $amount)
    {
        // You can hook into Braintree transaction logging here if needed
        return $this;
    }

    public function capture(\Magento\Payment\Model\InfoInterface $payment, $amount)
    {
        // No-op: capture happens externally
        return $this;
    }
}
