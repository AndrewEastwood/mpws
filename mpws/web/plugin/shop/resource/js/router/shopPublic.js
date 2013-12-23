APP.Modules.register("plugin/shop/router/shopPublic", [window], [
    'lib/underscore',
    'lib/backbone',
    // 'plugin/shop/view/shop',
], function (wnd, app, Sandbox, _, Backbone) {

    // start site routing
    //var view = null;

    var _pages = {};

    function Router (options) {

        // this.view = new pluginShopView(options);
        // this.view.router = this;

        // app.log(true, 'plugin/shop/router/shop constructor', options);

        // if (this.Controller)
        //  return;
        var self = this;

        this.navMap = {
            "site_home": "",
            "shop_home": "shop",
            // "shop_latest": "shop/latest",
            "shop_catalog_category": "shop/catalog/:category",
            "shop_catalog": "shop/catalog/",
            // "shop_catalog_category_brand": "shop/catalog/:category/:brand",
            "shop_product": "shop/product/:product",
            "shop_wizard": "shop/wizard",
            "shop_cart_view": "shop/cart",
            // "shop_cart_checkout_view": "shop/cart/checkout",
            // "shop_cart_checkout_preview": "shop/cart/checkout/preview",
            // "shop_cart_checkout_save": "shop/cart/checkout/preview"
        };

        var _routeDispatchers = {
            routes: _.invert(this.navMap)
        };

        _routeDispatchers.site_home = function () {
            _routeDispatchers.shop_home();
        };
        _routeDispatchers.shop_home = function () {
            // this is the same as shop_home
            // so that's why we're using shop_home
            if (!_pages.shop_home)
                APP.Modules.require(["plugin/shop/page/pageShopHome"], function (pageShopHome){
                    var p = new pageShopHome(options);
                    // app.log(true, 'the "plugin/shop/page/pageShopHome" is being rendered');
                    p.render();
                    _pages.shop_home = p;
                });
            else
                _pages.shop_home.render();
        };
        _routeDispatchers.shop_catalog = function (categoryId) {
            if (!_pages.shop_catalog)
                APP.Modules.require(["plugin/shop/page/pageShopCatalog"], function (pageShopCatalog){
                    var p = new pageShopCatalog(options);
                    // app.log(true, 'the "plugin/shop/page/pageShopCatalog" is being rendered');
                    p.render(categoryId);
                    _pages.shop_catalog = p;
                });
            else
                _pages.shop_catalog.render(categoryId);
        };
        _routeDispatchers.shop_catalog_category = function (categoryId) {
            app.log(true, '_routeDispatchers.shop_catalog_category', categoryId);
            _routeDispatchers.shop_catalog(categoryId);
        };
        // display particular product
        _routeDispatchers.shop_product = function (productId) {
            app.log(true, 'shop_product is active router', productId);
            if (!_pages.shop_product)
                APP.Modules.require(["plugin/shop/page/pageProductEntryStandalone"], function (pageProductEntryStandalone){
                    var p = new pageProductEntryStandalone(options);
                    p.render(productId);
                    _pages.shop_product = p;
                });
            else
                _pages.shop_product.render(productId);
        };
        _routeDispatchers.shop_wizard = function () {};

        // app.log(true, 'plugin/shop/router/shop creating new instance', _.invert(this.navMap));
        var _Controller = Backbone.Router.extend(_routeDispatchers);

        this.controller = new _Controller();
        // app.log(true, 'Router.controller', this.controller);

        // window.shop = this;

        this.refreshPage = function () {
            Backbone.history.loadUrl();
        }

        this.isRouteActive = function (routePath) {
            return Backbone.history.fragment === routePath;
        }

        this.redirectOrRefreshPage = function (routePath) {
            if (Backbone.history.fragment === routePath)
                self.refreshPage();
            else
                self.controller.navigate(routePath, {trigger: true});
        }
    }

    return Router;

});