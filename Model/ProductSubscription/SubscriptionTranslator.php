<?php

namespace Drd\Subscribe\Model\ProductSubscription;

class SubscriptionTranslator
{
    public function getFormatFrequency(string $code): string
    {
        $map = [
            '1w' => __('Every week'),
            '2w' => __('Every 2 weeks'),
            '1m' => __('Every month'),
            '3m' => __('Every 3 months'),
        ];

        return $map[$code] ?? $code;
    }
}
