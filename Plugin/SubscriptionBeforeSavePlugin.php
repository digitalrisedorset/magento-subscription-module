<?php

namespace Drd\Subscribe\Plugin;

use Drd\Subscribe\Model\ProductSubscription\NextSubscriptionDateCalculator;
use Drd\Subscribe\Model\Subscription;
use Magento\Framework\Stdlib\DateTime;

class SubscriptionBeforeSavePlugin
{
    private NextSubscriptionDateCalculator $nextSubscriptionDateCalculator;

    /**
     * @param NextSubscriptionDateCalculator $nextSubscriptionDateCalculator
     */
    public function __construct(
        NextSubscriptionDateCalculator $nextSubscriptionDateCalculator
    ) {
        $this->nextSubscriptionDateCalculator = $nextSubscriptionDateCalculator;
    }

    public function beforeSave(Subscription $subject)
    {
        // Normalize status
        if ($subject->getStatus()) {
            $subject->setStatus(strtolower($subject->getStatus()));
        }

        // If status is not set, and data seems valid, default to 'active'
        if (!$subject->getStatus() && $this->isValid($subject)) {
            $subject->setStatus('active');
        }

        if (!$subject->getId()) {
            $subject->setCreatedAt(
                (new \DateTime())->format(DateTime::DATETIME_PHP_FORMAT)
            );
        }

        $subject->setUpdatedAt(
            (new \DateTime())->format(DateTime::DATETIME_PHP_FORMAT)
        );

        // Set next_order_date if missing
        if (!$subject->getNextOrderDate() && $subject->getRecurrence()) {
            $subject->setNextOrderDate(
                $this->nextSubscriptionDateCalculator->calculateNextDate($subject)
            );
        }

        return [$subject];
    }

    private function isValid(Subscription $subject): bool
    {
        return $subject->getOrderId() && $subject->getSku() && $subject->getRecurrence();
    }
}
