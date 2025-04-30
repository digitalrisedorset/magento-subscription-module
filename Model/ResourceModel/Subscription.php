<?php

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
