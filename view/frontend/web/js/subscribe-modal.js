define([
    'jquery',
    'Magento_Ui/js/modal/modal'
], function ($, modal) {
    'use strict';

    return function (config, element) {
        const $modal = $(config.modalSelector);

        if (!$modal.length) {
            return;
        }

        const modalInstance = modal({
            type: 'popup',
            responsive: true,
            title: 'Subscribe to Product',
            buttons: []
        }, $modal);

        $(element).on('click', function () {
            $modal.modal('openModal');
        });
    };
});
