define([
    'plugins/shop/site/js/view/cartEmbedded',
    'plugins/shop/site/js/view/orderTrackingButton',
    'plugins/shop/site/js/view/widgetAddress',
    'plugins/shop/site/js/view/widgetExchangeRates',
    'plugins/shop/site/js/view/menuCatalogBar'
], function (CartEmbedded, OrderTrackingButton, Address, ExchangeRates, CatalogBar) {

    var SiteWidgets = function (models) {

        var renderItems = [];

        var addr = new Address();
        addr.collection.fetch({reset: true});
        renderItems.push({
            name: 'CommonWidgetsTop',
            el: addr.$el,
            append: true
        });

        // show exchange rates selector
        // debugger;
        if (APP.instances.shop.settings.MISC.ShowSiteCurrencySelector) {
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

        var cBar = new CatalogBar();
        cBar.model.fetch({reset: true});
        renderItems.push({
            name: 'CommonWidgetsTop',
            el: cBar.$el,
            append: true
        });

        APP.Sandbox.eventSubscribe('global:loader:complete', function () {
            APP.Sandbox.eventNotify('global:content:render', renderItems);
        });
    };

    SiteWidgets.ExchangeRates = ExchangeRates;
    return SiteWidgets;

});