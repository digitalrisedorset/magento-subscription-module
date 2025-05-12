<?php

namespace Drd\Subscribe\Model\ProductSubscription;

class SubscriptionTranslator
{
    public function getFormatFrequency(string $code): string
    {
        $map = [
            '1w' => __('Every Week'),
            '2w' => __('Every 2 Weeks'),
            '1m' => __('Every Month'),
            '3m' => __('Every 3 MAonths'),
        ];

        return $map[$code] ?? $code;
    }
}
