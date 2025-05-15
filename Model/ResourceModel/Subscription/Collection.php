<?php
/**
 * Copyright © Digital Rise Dorset. All rights reserved.YING.txt for license details.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);


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
