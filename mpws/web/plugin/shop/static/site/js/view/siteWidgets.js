define("plugin/shop/site/js/view/siteWidgets", [
    'default/js/lib/sandbox',
    'plugin/shop/site/js/view/cartEmbedded',
    'plugin/shop/site/js/view/orderTrackingButton'
], function (Sandbox, CartEmbedded, OrderTrackingButton) {

    // inject tracking order
    var orderTrackingButton = new OrderTrackingButton();
    orderTrackingButton.render();

    // inject embedded shopping cart
    var cartEmbedded = new CartEmbedded();
    cartEmbedded.render();

    Sandbox.eventSubscribe('global:loader:complete', function () {
        Sandbox.eventNotify('global:content:render', [
            {
                name: 'CommonWidgetsTop',
                el: orderTrackingButton.$el,
                append: true
            },
            {
                name: 'CommonWidgetsTop',
                el: cartEmbedded.$el,
                append: true
            }
        ]);
    });

});