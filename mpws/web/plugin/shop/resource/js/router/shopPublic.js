APP.Modules.register("plugin/shop/router/shopPublic", [window], [
    'lib/underscore',
    'lib/backbone',
    'plugin/shop/view/shop',
], function (wnd, app, Sandbox, _, Backbone, pluginShopView) {

    // start site routing
    //var view = null;

    var _pages = {};

    function Router (options) {

        this.view = new pluginShopView(options);
        this.view.router = this;

        // app.log(true, 'plugin/shop/router/shop constructor', options);

        // if (this.Controller)
        //  return;
        var self = this;

        this.navMap = {
            "site_home": "",
            "shop_home": "shop",
            "shop_latest": "shop/latest",
            "shop_catalog": "shop/catalog",
            "shop_products_category": "shop/catalog/:category",
            "shop_products_category_brand": "shop/catalog/:category/:brand",
            "shop_product": "shop/product/:product",
            "shop_wizard": "shop/wizard",
            "shop_cart_view": "shop/cart",
            "shop_cart_checkout_view": "shop/cart/checkout",
            "shop_cart_checkout_preview": "shop/cart/checkout/preview",
            "shop_cart_checkout_save": "shop/cart/checkout/preview"
        };

        // app.log(true, 'plugin/shop/router/shop creating new instance', _.invert(this.navMap));
        var _Controller = Backbone.Router.extend({
            routes: _.invert(this.navMap),
            // shop default page
            site_home: function () {
                if (!_pages.site_home)
                    APP.Modules.require(["plugin/shop/view/pageShopHome"], function (pageShopHome){
                        var p = new pageShopHome(options);
                        app.log(true, 'the "plugin/shop/view/pageShopHome" is being rendered');
                        p.render();
                        _pages.site_home = p;
                    });
                else
                    _pages.site_home.render();
            },
            shop_home: function () {
                if (!_pages.shop_home)
                    APP.Modules.require(["plugin/shop/view/pageShopHome"], function (pageShopHome){
                        var p = new pageShopHome(options);
                        app.log(true, 'the "plugin/shop/view/pageShopHome" is being rendered');
                        p.render();
                        _pages.shop_home = p;
                    });
                else
                    _pages.shop_home.render();
            },
            // display shop latest stuff
            shop_latest: function (route, name, callback) {
                // self.view.pageShopProductListLatest(route, name, callback);
                // self.shopProductsLatest(route, name, callback);
                app.log(true, 'Router.prototype.shopProductsLatest');
                self.view.pageShopProductListLatest(route, name, callback);
            },
            // display catalog
            shop_catalog: function (route, name, callback) {
                // self.view.pageShopCatalog(route, name, callback);
                // self.shopCatalog(route, name, callback);
                self.view.pageShopCatalog(route, name, callback);
            },
            // display category
            shop_products_category: function (categoryId) {
                // here we handle different stuff:
                // sorting by [name, date, price, popularity, etc]
                // pagination
                // 
                // self.view.pageShopCatalogByCategory(route, name, callback);
                // self.shopCatalogByCategory(route, name, callback);
                app.log(true, 'shop_products_category', categoryId);
                self.view.pageShopProductsByCategory(categoryId);
            },
            // display category
            shop_products_category_brand: function () {
                // here we handle different stuff:
                // sorting by [name, date, price, popularity, etc]
                // pagination
                // 
                // self.view.pageShopCatalogByCategoryAndBrand(route, name, callback);
                // self.shopCatalogByCategoryAndBrand();
                self.view.pageShopCatalogByCategoryAndBrand();
            },
            shop_cart_view: function () {
                // self.view.pageShopCart();
                // self.shopCart();
                self.view.pageShopCart();
            },
            shop_cart_checkout_view: function () {
                // self.shopCartCheckout();
                self.view.pageShopCartCheckout();
            },
            shop_cart_checkout_preview: function () {
                // self.shopCartCheckout();
                self.view.pageShopCartCheckoutPreview();
            },
            shop_cart_checkout_save: function () {
                // self.shopCartCheckout();
                self.view.pageShopCartCheckoutSave();
            },
            // display particular product
            shop_product: function (productId, name, callback) {
                app.log(true, 'shop_product is active router', productId);
                if (!_pages.shop_product)
                    APP.Modules.require(["plugin/shop/view/pageProductEntryStandalone"], function (pageProductEntryStandalone){
                        var p = new pageProductEntryStandalone(options);
                        p.render(productId);
                        _pages.shop_product = p;
                    });
                else
                    _pages.shop_product.render(productId);
            },
            shop_wizard: function () {
                self.view.pageShoppingWizard();
            }

        });

        this.controller = new _Controller();
        app.log(true, 'Router.controller', this.controller);

        window.shop = this;

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