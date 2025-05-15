<?php
/**
 * Copyright © Digital Rise Dorset. All rights reserved.YING.txt for license details.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);


namespace Drd\Subscribe\Plugin\Cart;

use Drd\Subscribe\Model\AddToCart\QuoteItemPriceHandler;
use Magento\Quote\Model\Quote\Item as QuoteItem;

class ApplySubscriptionPricing
{
    private QuoteItemPriceHandler $quoteItemPriceHandler;

    /**
     * @param QuoteItemPriceHandler $quoteItemPriceHandler
     */
    public function __construct(QuoteItemPriceHandler $quoteItemPriceHandler)
    {
        $this->quoteItemPriceHandler = $quoteItemPriceHandler;
    }

    public function afterAddProduct(
        \Magento\Checkout\Model\Cart $subject,
                                     $result,
                                     $product,
                                     $requestInfo = null
    ) {
        // Find the quote item that was just added
        $quote = $subject->getQuote();
        /** @var QuoteItem $quoteItem */
        $quoteItem = $quote->getItemByProduct($product);

        if (!$quoteItem) {
            return $result;
        }

        $this->quoteItemPriceHandler->addSubscriptionDiscount($quoteItem, $product, $requestInfo);

        return $result;
    }
}
