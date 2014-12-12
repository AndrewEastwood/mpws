define("plugin/shop/site/js/view/siteWidgets", [
    'default/js/lib/sandbox',
    'plugin/shop/site/js/view/cartEmbedded',
    'plugin/shop/site/js/view/orderTrackingButton',
    'plugin/shop/site/js/view/widgetAddress',
    'plugin/shop/site/js/view/widgetExchangeRates'
], function (Sandbox, CartEmbedded, OrderTrackingButton, Address, ExchangeRates) {

    var SiteWidgets = function (models) {

        var renderItems = [];

        // show exchange rates selector
        // debugger;
        if (APP.instances.shop.settings.ShowSiteCurrencySelector) {
            var rates = new ExchangeRates();
            rates.render();
            renderItems.push({
                name: 'CommonWidgetsTop',
                el: rates.$el,
                append: true
            });
        }

        // inject tracking order
        var orderTrackingButton = new OrderTrackingButton();
        orderTrackingButton.render();
        renderItems.push({
            name: 'CommonWidgetsTop',
            el: orderTrackingButton.$el,
            append: true
        });

        // inject embedded shopping cart
        var cartEmbedded = new CartEmbedded({model: models.order});
        cartEmbedded.render();
        renderItems.push({
            name: 'CommonWidgetsTop',
            el: cartEmbedded.$el,
            append: true
        });

        var addr = new Address();
        addr.collection.fetch({reset: true});
        renderItems.push({
            name: 'CommonWidgetsTop',
            el: addr.$el,
            append: true
        });

        Sandbox.eventSubscribe('global:loader:complete', function () {
            Sandbox.eventNotify('global:content:render', renderItems);
        });
    };

    SiteWidgets.ExchangeRates = ExchangeRates;
    return SiteWidgets;

});