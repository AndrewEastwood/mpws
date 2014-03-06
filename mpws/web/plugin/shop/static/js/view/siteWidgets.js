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

    // return {
    //     render: function () {
            Sandbox.eventSubscribe('global:loader:complete', function () {
                Site.placeholders.common.widgetsTop.append(orderTrackingButton.$el);
                Site.placeholders.common.widgetsTop.append(cartEmbedded.$el);
            });
    //     }
    // }

});