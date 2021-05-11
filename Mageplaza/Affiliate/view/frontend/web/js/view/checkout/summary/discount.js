define(
    [
        'jquery',
        'Magento_Checkout/js/view/summary/abstract-total',
        'Magento_Checkout/js/model/totals'
    ],
    function ($, Component, totals) {
        "use strict";
        return Component.extend({
            defaults: {
                template: 'Mageplaza_Affiliate/checkout/summary/discount'
            },
            isDisplayedDiscount: function () {
                return totals.getSegment('affiliate_discount').value < 0;
            },
            getDiscount: function () {
                let price = totals.getSegment('affiliate_discount').value;
                return this.getFormattedPrice(price);
            }
        });
    }
);
