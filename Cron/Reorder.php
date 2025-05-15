<?php
/**
 * Copyright © Digital Rise Dorset. All rights reserved.YING.txt for license details.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);


namespace Drd\Subscribe\Cron;

use Drd\Subscribe\Model\SubscriptionReorderProcessor;

class Reorder
{
    private SubscriptionReorderProcessor $processor;

    public function __construct(SubscriptionReorderProcessor $processor)
    {
        $this->processor = $processor;
    }

    public function execute()
    {
        $this->processor->process();
    }
}
