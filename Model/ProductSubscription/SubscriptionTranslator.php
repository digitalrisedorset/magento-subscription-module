<?php
/**
 * Copyright © Digital Rise Dorset. All rights reserved.YING.txt for license details.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);


namespace Drd\Subscribe\Model\ProductSubscription;

class SubscriptionTranslator
{
    public function getFormatFrequency(string $code): string
    {
        $map = [
            '1w' => __('Every Week'),
            '2w' => __('Every 2 Weeks'),
            '1m' => __('Every Month'),
            '3m' => __('Every 3 Months'),
        ];

        $sentence = $map[$code] ?? $code;
        return (string) $sentence;
    }
}
