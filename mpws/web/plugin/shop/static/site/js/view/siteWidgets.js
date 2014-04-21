define("plugin/shop/js/view/siteWidgets", [
    'default/js/lib/sandbox',
    'customer/js/site',
    'plugin/shop/js/view/cartEmbedded',
    'plugin/shop/js/view/orderTrackingButton'
], function (Sandbox, Site, CartEmbedded, OrderTrackingButton) {

    // inject tracking order
    var orderTrackingButton = new OrderTrackingButton();
    orderTrackingButton.fetchAndRender();

    // inject embedded shopping cart
    var cartEmbedded = new CartEmbedded();
    cartEmbedded.fetchAndRender();

    Sandbox.eventSubscribe('global:loader:complete', function () {
        Sandbox.eventNotify('global:content:render', [
            {
                name: 'ShopWidgetOrderStatusButton',
                el: orderTrackingButton.$el,
                append: true
            },
            {
                name: 'ShopWidgetShoppingCartEmbedded',
                el: cartEmbedded.$el,
                append: true
            }
        ]);
    });

});