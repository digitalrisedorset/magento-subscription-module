define([
    'jquery',
    'mage/translate',
    'Magento_Customer/js/customer-data',
    'Magento_Ui/js/model/messageList'
], function ($, $t, customerData, messageList) {
    'use strict';

    $.widget('drd.subscribeButton', {
        _create: function () {
            var self = this;
            this.element.on('submit', function (e) {
                e.preventDefault();
                self.submitForm($(this));
            });
        },

        submitForm: function (form) {
            var self = this;
            var button = $('#subscribe-button');

            this.disableButton(button)

            $.ajax({
                url: self.element.attr('action'),
                data: self.element.serialize(),
                type: 'POST',
                dataType: 'json',
                success: function () {

                },
                error: function () {
                    // Handle error
                    console.log('could not add the subscription to the cart')
                },
                complete: function () {
                    console.log('added subscription complete')
                    self.enableButton(button)
                    var sections = ['cart'];
                    customerData.reload(sections, true);
                }
            });
        },

        disableButton: function (button) {
            var loader = button.find('.loader');
            loader.show();
            button.prop('disabled', true);
            button.find('span').text('Subscribing...');
        },

        enableButton: function (button) {
            button.prop('disabled', false);
            button.find('span').text('Subscribe');
            var loader = button.find('.loader');
            loader.hide();
        }
    });

    return $.drd.subscribeButton;
});
