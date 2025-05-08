define(['jquery'], function ($) {
    'use strict';

    return function (widget) {
        $.widget('mage.priceBox', widget, {
            reloadPrice: function () {
                this._super();

                // Initial update on load
                this._updateSuperAttributes();

                // Run every time variation changes
                $('#product_addtocart_form').on('updateProductComplete', this._updateSuperAttributes);
            },

            _updateSuperAttributes: function () {
                var attributes = {};

                $('.super-attribute-select').each(function () {
                    var name = $(this).attr('name');
                    var match = name.match(/\[(\d+)\]/); // extract the attribute ID
                    if (match) {
                        var attrId = match[1];
                        var val = $(this).val();
                        if (val) {
                            attributes[attrId] = val;
                        }
                    }
                });

                // Optional: log or serialize to a hidden input
                var serialized = JSON.stringify(attributes);
                $('#super_attribute_snapshot').val(serialized);
            }
        });

        return $.mage.priceBox;
    }
});
