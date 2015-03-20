define([
    'plugins/shop/site/js/view/cartEmbedded',
    'plugins/shop/site/js/view/orderTrackingButton',
    'plugins/shop/site/js/view/widgetAddress',
    'plugins/shop/site/js/view/widgetExchangeRates',
    'plugins/shop/site/js/view/menuCatalogBar'
], function (CartEmbedded, OrderTrackingButton, Address, ExchangeRates, CatalogBar) {

    function SiteWidgets (models) {

        // addres
        var addr = new Address();
        addr.collection.fetch({reset: true});

        // show exchange rates selector
        var rates = new ExchangeRates();
        if (APP.instances.shop.settings.MISC.ShowSiteCurrencySelector) {
            rates.render();
        }

        // inject tracking order
        var orderTrackingButton = new OrderTrackingButton();
        orderTrackingButton.render();

        // inject embedded shopping cart
        var cartEmbedded = new CartEmbedded({model: models.order});
        cartEmbedded.render();

        // catalog navigation panel
        var cBar = new CatalogBar();
        cBar.model.fetch({reset: true});

        APP.injectHtml('ShopWidgetAddresses', addr.el);
        APP.injectHtml('ShopWidgetExchangeRates', rates.el);
        APP.injectHtml('ShopWidgetTrackOrderButton', orderTrackingButton.el);
        APP.injectHtml('ShopWidgetCartButton', cartEmbedded.el);
        APP.injectHtml('ShopWidgetCatalogBar', cBar.el);
    };

    // some static props
    SiteWidgets.ExchangeRates = ExchangeRates;

    return SiteWidgets;

});