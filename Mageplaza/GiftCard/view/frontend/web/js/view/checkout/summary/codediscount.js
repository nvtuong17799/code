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
                template: 'Mageplaza_GiftCard/checkout/summary/codediscount'
            },
            isDisplayedDiscount: function () {
                if(totals.getSegment('giftcard_discount').value < 0) {
                    return true;
                }
                return false;
            },
            getDiscount: function () {
                var price = totals.getSegment('giftcard_discount').value;
                return this.getFormattedPrice(price);
            }
        });
    }
);
