<?php
/**
 * Copyright © Digital Rise Dorset. All rights reserved.YING.txt for license details.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);


namespace Drd\Subscribe\Model\ProductSubscription;

use Drd\Subscribe\Model\Subscription;
use Magento\Framework\Stdlib\DateTime;

class NextSubscriptionDateCalculator
{
    public function calculateNextDate(Subscription $subscription): string
    {
        // Retrieve the current next_order_date or default to now
        $currentDate = $subscription->getNextOrderDate();
        $date = $currentDate ? new \DateTime($currentDate) : new \DateTime();

        switch (strtolower($subscription->getRecurrence())) {
            case '1w':
                $interval = new \DateInterval('P1W');
                break;
            case '1m':
                $interval = new \DateInterval('P1M');
                break;
            default:
                $interval = new \DateInterval('P1M');
                break;
        }

        // If skip_next_order is true, add the interval twice
        if ($subscription->getSkipNextOrder()) {
            $date->add($interval);
            $date->add($interval);
        } else {
            $date->add($interval);
        }

        return $date->format(DateTime::DATETIME_PHP_FORMAT);
    }
}
