<?php

namespace Drd\Subscribe\ViewModel;

use Drd\Subscribe\Model\ProductSubscription\SubscriptionTranslator;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Framework\Phrase;
use DateTime;
use DateTimeZone;

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

        $days = $this->getNumberDaysBetweenTwoDates($now, $next);

        if ($days === 0) {
            return __('Today');
        } elseif ($days > 0 && $days <= 7) {
            return $days === 1
                ? __('In 1 day')
                : __('In %1 days', $days);
        } elseif ($days > 7) {
            return __('On %1', $this->timezone->formatDate($next, \IntlDateFormatter::MEDIUM));
        } else {
            return __('Overdue (%1)', $this->timezone->formatDate($next, \IntlDateFormatter::MEDIUM));
        }
    }

    public function formatStatus($subscription): string
    {
        return ucfirst(strtolower($subscription->getStatus()));
    }

    public function formatRecurrence($subscription): string
    {
        return ucwords(strtolower(
            $this->subscriptionTranslator->getFormatFrequency(
                $subscription->getRecurrence()
            )
        ));
    }

    private function getNumberDaysBetweenTwoDates($now, $next)
    {
        $diff = $now->diff($next);
        $seconds = ($diff->days * 24 * 3600) +
            ($diff->h * 3600) +
            ($diff->i * 60) +
            $diff->s;

        return ceil($seconds / 86400);
    }
}
