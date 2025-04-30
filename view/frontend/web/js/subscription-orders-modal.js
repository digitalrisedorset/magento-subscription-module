define([
    'jquery',
    'Magento_Ui/js/modal/modal'
], function ($, modal) {
    'use strict';

    return function (config, element) {
        var $element = $(element);
        var parentOrderId = config.parentOrderId;
        var ajaxUrl = config.ajaxUrl;

        $element.on('click', function (e) {
            e.preventDefault();

            // Create a container for the modal content
            var $modalContent = $('<div/>').attr('id', 'subscription-orders-modal-content');

            // Define modal options
            var options = {
                type: 'popup',
                responsive: true,
                innerScroll: true,
                title: 'Subscription Orders',
                buttons: [{
                    text: $.mage.__('Close'),
                    class: 'action-secondary',
                    click: function () {
                        this.closeModal();
                    }
                }]
            };

            // Initialize and open the modal
            var popup = modal(options, $modalContent);
            $modalContent.modal('openModal');

            // Load content via AJAX
            $.ajax({
                url: ajaxUrl,
                type: 'GET',
                data: { parent_order_id: parentOrderId },
                success: function (response) {
                    $modalContent.html(response);
                },
                error: function () {
                    $modalContent.html('<p>' + $.mage.__('An error occurred while loading the subscription orders.') + '</p>');
                }
            });
        });
    };
});
