define("plugin/shop/site/js/view/orderTrackingButton", [
    'default/js/view/mView',
    'default/js/plugin/hbs!plugin/shop/site/hbs/orderTrackingButton'
], function (MView, tpl) {

    var CartEmbedded = MView.extend({
        className: 'btn-group shop-order-tracking-button',
        id: 'shop-order-tracking-button-ID',
        template: tpl
    });

    return CartEmbedded;

});