<?php

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
