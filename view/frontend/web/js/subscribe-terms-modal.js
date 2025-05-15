require(['jquery', 'Magento_Ui/js/modal/modal'], function ($, modal) {
    var modalInitialized = false;

    $('.view-terms-link').on('click', function (e) {
        e.preventDefault();

        var blockId = $(this).data('cms-block');

        if (!modalInitialized) {
            modal({
                type: 'popup',
                responsive: true,
                innerScroll: true,
                title: 'Subscription Terms & Conditions',
                buttons: []
            }, $('#subscription-terms-modal'));

            modalInitialized = true;
        }

        $('#subscription-terms-modal-content').html('Loading...');

        $.get('/subscribe/plan/terms', { block_id: blockId }, function (response) {
            $('#subscription-terms-modal-content').html(response);
            $('#subscription-terms-modal').modal('openModal');
        }).fail(function () {
            $('#subscription-terms-modal-content').html('Unable to load terms. Please try again.');
            $('#subscription-terms-modal').modal('openModal');
        });
    });
});
