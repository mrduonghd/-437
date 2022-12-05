define(
    [
        'uiComponent',
        'Magento_Checkout/js/model/payment/renderer-list',
    ],
    function (
        Component,
        rendererList
    ) {
        'use strict';

        rendererList.push(
            {
                type: 'paypal_checkout',
                component: 'Mpx_PaypalCheckout/js/view/payment/method-renderer/paypal-checkout'
            }
        );
        return Component.extend({});
    }
);
