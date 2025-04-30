<?php
namespace Drd\Subscribe\Plugin;

use Drd\Subscribe\Model\QuoteItemOptionsExtractor;
use Magento\Checkout\Block\Cart\Item\Renderer as CartItemRenderer;
use Magento\Framework\Serialize\SerializerInterface;

class CartItemOptionsPlugin
{
    private QuoteItemOptionsExtractor $quoteItemOptionsExtractor;

    /**
     * @param QuoteItemOptionsExtractor $quoteItemOptionsExtractor
     */
    public function __construct(
        QuoteItemOptionsExtractor $quoteItemOptionsExtractor
    ) {
        $this->quoteItemOptionsExtractor = $quoteItemOptionsExtractor;
    }

    public function afterGetOptionList(CartItemRenderer $subject, $result)
    {
        /** @var \Magento\Quote\Model\Quote\Item $item */
        $item = $subject->getItem();
        $customOptions = $this->quoteItemOptionsExtractor->getQuoteItemCustomOption($item);

        if ($customOptions === null) {
            return $result;
        }

        return array_merge($result, $customOptions);
    }
}
