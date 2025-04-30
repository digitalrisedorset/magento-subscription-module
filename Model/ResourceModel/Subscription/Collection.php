<?php

namespace Drd\Subscribe\Model\ResourceModel\Subscription;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Drd\Subscribe\Model\Subscription as SubscriptionModel;
use Drd\Subscribe\Model\ResourceModel\Subscription as SubscriptionResource;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(SubscriptionModel::class, SubscriptionResource::class);
    }
}
