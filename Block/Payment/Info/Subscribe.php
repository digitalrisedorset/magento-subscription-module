<?php
/**
 * Copyright © Digital Rise Dorset. All rights reserved.YING.txt for license details.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);


namespace Drd\Subscribe\Block\Payment\Info;

use Magento\Payment\Block\Info;

class Subscribe extends Info
{
    protected $_template = 'Drd_Subscribe::order/view/payment/info.phtml';

    public function getTransactionId()
    {
        $info = $this->getInfo()->getAdditionalInformation();
        if (!empty($info['last_trans_id'])) {
            return $info['last_trans_id'];
        }
    }

    public function getPaymentType()
    {
        $info = $this->getInfo()->getAdditionalInformation();
        if (!empty($info['payment_type'])) {
            return $info['payment_type'];
        }
    }

    public function getPaymentDescription()
    {
        $info = $this->getInfo()->getAdditionalInformation();
        if (!empty($info['metadata'])) {
            return $info['metadata']['type'];
        }
    }
}
