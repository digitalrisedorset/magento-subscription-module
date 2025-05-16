var config = {
    map: {
        '*': {
            subscriptionModal: 'Drd_Subscribe/js/subscription-orders-modal',
            subscribeButton: 'Drd_Subscribe/js/subscribe-button'
        }
    },
    config: {
        mixins: {
            'Magento_Catalog/js/price-box': {
                'Drd_Subscribe/js/price-box-mixin': true
            }
        }
    }
};
