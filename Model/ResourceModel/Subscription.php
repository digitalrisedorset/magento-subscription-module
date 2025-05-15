<?php
/**
 * Copyright © Digital Rise Dorset. All rights reserved.YING.txt for license details.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);


namespace Drd\Subscribe\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Subscription extends AbstractDb
{
    const SUBSCRIPTION_TABLE = 'drd_subscribe_subscription';

    protected function _construct()
    {
        $this->_init(Subscription::SUBSCRIPTION_TABLE, 'subscription_id');
    }
}
