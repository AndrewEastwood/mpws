define("plugin/shop/site/js/view/siteWidgets", [
    'default/js/lib/sandbox',
    'plugin/shop/site/js/view/cartEmbedded',
    'plugin/shop/site/js/view/orderTrackingButton',
    'plugin/shop/site/js/view/widgetAddress',
    'plugin/shop/site/js/view/widgetExchangeRates'
], function (Sandbox, CartEmbedded, OrderTrackingButton, Address, ExchangeRates) {

    var SiteWidgets = function (models) {

        // inject tracking order
        var orderTrackingButton = new OrderTrackingButton();
        orderTrackingButton.render();

        // inject embedded shopping cart
        var cartEmbedded = new CartEmbedded({
            model: models.order
        });
        cartEmbedded.render();

        var addr = new Address();
        addr.collection.fetch({reset: true});

        var rates = new ExchangeRates();
        rates.render();

        Sandbox.eventSubscribe('global:loader:complete', function () {
            Sandbox.eventNotify('global:content:render', [
                {
                    name: 'CommonWidgetsTop',
                    el: rates.$el,
                    append: true
                },
                {
                    name: 'CommonWidgetsTop',
                    el: orderTrackingButton.$el,
                    append: true
                },
                {
                    name: 'CommonWidgetsTop',
                    el: cartEmbedded.$el,
                    append: true
                },
                {
                    name: 'CommonWidgetsTop',
                    el: addr.$el,
                    append: true
                }
            ]);
        });
    };

    SiteWidgets.ExchangeRates = ExchangeRates;

    return SiteWidgets;

});