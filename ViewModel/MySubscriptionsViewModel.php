<?php
/**
 * Copyright © Digital Rise Dorset. All rights reserved.YING.txt for license details.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);


namespace Drd\Subscribe\ViewModel;

use Drd\Subscribe\Model\ProductSubscription\SubscriptionTranslator;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Framework\Phrase;

class MySubscriptionsViewModel implements ArgumentInterface
{
    private TimezoneInterface $timezone;
    private SubscriptionTranslator $subscriptionTranslator;

    /**
     * @param TimezoneInterface $timezone
     * @param SubscriptionTranslator $subscriptionTranslator
     */
    public function __construct(
        TimezoneInterface $timezone,
        SubscriptionTranslator $subscriptionTranslator
    ) {
        $this->timezone = $timezone;
        $this->subscriptionTranslator = $subscriptionTranslator;
    }

    public function formatCreatedAt($subscription): string
    {
        return $this->timezone->formatDate($subscription->getCreatedAt(), \IntlDateFormatter::MEDIUM);
    }

    /**
     * @param $subscription
     * @return Phrase
     * @throws \Exception
     */
    public function formatNextOrderDate($subscription): Phrase
    {
        $now = new \DateTime('now', new \DateTimeZone($this->timezone->getConfigTimezone()));
        $next = new \DateTime($subscription->getNextOrderDate(), new \DateTimeZone($this->timezone->getConfigTimezone()));

        $interval = $now->diff($next);
        $isPast = $next < $now;

        $formattedDate = $this->timezone->formatDateTime($next, \IntlDateFormatter::MEDIUM, \IntlDateFormatter::SHORT); // includes time

        if ($interval->days === 0 && !$isPast) {
            return __('Today (%1)', $formattedDate);
        }

        if (!$isPast && $interval->days <= 7) {
            return $interval->days === 1
                ? __('In 1 day (%1)', $formattedDate)
                : __('In %1 days (%2)', $interval->days, $formattedDate);
        }

        if (!$isPast) {
            return __('On %1', $formattedDate);
        }

        return __('Overdue (%1)', $formattedDate);
    }

    public function getDisplayStatus($subscription): Phrase
    {
        $status = $subscription->getStatus();
        $skipNext = $subscription->getSkipNextOrder();

        if ($status === 'active' && $skipNext) {
            return __('Active (Next Order Skipped)');
        }

        return __(ucfirst($status));
    }

    public function formatRecurrence($subscription): string
    {
        return ucwords(strtolower(
            $this->subscriptionTranslator->getFormatFrequency(
                $subscription->getRecurrence()
            )
        ));
    }
}
