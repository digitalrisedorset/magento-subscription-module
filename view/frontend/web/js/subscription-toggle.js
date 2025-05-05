define(['jquery'], function ($) {
    'use strict';

    /**
     * Validates the form submission based on the selected mode and plan.
     * @param {jQuery} $root
     */
    function bindSubscriptionFormValidation($root) {
        const $form = $root.closest('form');

        $form.off('submit.drdSubscribe').on('submit.drdSubscribe', function (e) {
            const isSubscribe = $root.find('input[name="purchase_mode"]:checked').val() === 'subscribe';
            const selectedPlan = $root.find('input[name="subscription_plan_id"]:checked').val();

            if (isSubscribe && !selectedPlan) {
                e.preventDefault();
                e.stopImmediatePropagation();
                alert('Please select a subscription plan to continue.');
                return false;
            }

            $('#selected_subscription_plan_id').val(selectedPlan);
        });
    }

    /**
     * Toggles the subscription-related UI.
     * @param {jQuery} $root
     * @param {boolean} isSubscribe
     */
    function toggleSubscriptionUI($root, isSubscribe) {
        $root.find('.subscription-plan-selector').toggle(isSubscribe);
        $root.find('.subscription-action').toggle(isSubscribe);
    }

    /**
     * Entry point for Magento data-mage-init.
     * @param {Object} config
     * @param {HTMLElement} element
     */
    return function (config, element) {
        const $root = $(element);

        // Initial toggle based on preselected value
        const initialMode = $root.find('input[name="purchase_mode"]:checked').val();
        toggleSubscriptionUI($root, initialMode === 'subscribe');

        // Toggle UI on purchase mode change
        $root.find('input[name="purchase_mode"]').on('change', function () {
            const isSubscribe = $(this).val() === 'subscribe';
            toggleSubscriptionUI($root, isSubscribe);
        });

        bindSubscriptionFormValidation($root);
    };
});
