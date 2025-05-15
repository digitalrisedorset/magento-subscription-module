<?php
/**
 * Copyright © Digital Rise Dorset. All rights reserved.YING.txt for license details.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);


namespace Drd\Subscribe\Model\Config;

use Drd\Subscribe\Model\Config\SubscriptionPlans\Reader;
use Magento\Framework\Config\CacheInterface;
use Magento\Framework\DataObject;
use Magento\Framework\Serialize\SerializerInterface;

class Data extends \Magento\Framework\Config\Data
{
    private Reader $reader;

    /**
     * @param Reader $reader
     * @param CacheInterface $cache
     * @param $cacheId
     * @param SerializerInterface|null $serializer
     */
    public function __construct(
        \Drd\Subscribe\Model\Config\SubscriptionPlans\Reader $reader,
        \Magento\Framework\Config\CacheInterface $cache,
        $cacheId = 'customer_group',
        SerializerInterface $serializer = null
    ) {
        parent::__construct($reader, $cache, $cacheId, $serializer);
        $this->reader = $reader;
    }

    /**
     * @param string $id
     * @return mixed|null
     */
    public function getById(string $id)
    {
        $plans = $this->get();
        $data = $plans[$id] ?? null;

        if ($data !== null) {
            return new DataObject(array_merge(['id' => $id], $data));
        }

        return null;
    }
}
