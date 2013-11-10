APP.Modules.register("plugin/shop/router/shop", [], [
    'lib/jquery',
    'lib/underscore',
    'lib/backbone',
    'lib/mpws.api',
    'lib/mpws.page',
    'plugin/shop/view/render',
    "lib/htmlComponents",
], function (app, Sandbox, $, _, Backbone, mpwsAPI, mpwsPage, pluginShopView, HtmlComponents) {

    // start site routing
    //var view = null;
    var _libHtml = new HtmlComponents();

    function Router (options) {

        this.view = new pluginShopView(options);
        this.view.router = this;

        app.log(true, 'plugin/shop/router/shop constructor', options);

        // if (this.Controller)
        //  return;
        var self = this;

        this.navMap = {
            "site_home": "",
            "shop_home": "shop",
            "shop_latest": "shop/latest",
            "shop_catalog": "shop/catalog",
            "shop_category": "shop/catalog/:category",
            "shop_category_brand": "shop/catalog/:category/:brand",
            "shop_product": "shop/product/:product",
            "shop_cart_view": "shop/cart",
            "shop_cart_checkout": "shop/cart/checkout"
        };

        app.log(true, 'plugin/shop/router/shop creating new instance', _.invert(this.navMap));
        var _Controller = Backbone.Router.extend({
            routes: _.invert(this.navMap),
            // shop default page
            site_home: function () {
                // self.view.pageShopHome();
                // self.shopHome();
                app.log(true, 'Router.prototype.shopHome');
                self.view.pageShopHome();
            },
            shop_home: function () {
                // self.view.pageShopHome();
                // self.shopHome();
                app.log(true, 'Router.prototype.shopHome');
                self.view.pageShopHome();
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
            shop_category: function () {
                // here we handle different stuff:
                // sorting by [name, date, price, popularity, etc]
                // pagination
                // 
                // self.view.pageShopCatalogByCategory(route, name, callback);
                // self.shopCatalogByCategory(route, name, callback);
                self.view.pageShopCatalogByCategory();
            },
            // display category
            shop_category_brand: function () {
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
            shop_cart_checkout: function () {
                // self.shopCartCheckout();
                self.view.pageShopCartCheckout();
            },
            // display particular product
            shop_product: function (productId, name, callback) {
                // app.log(true, 'shop_product', productId);
                // self.view.pageShopProductItemByID(productId);
                // self.shopProductItem(productId, name, callback);
                app.log(true, 'shop_product')
                self.view.pageShopProductItemByID(productId);
            }

        });

        this.controller = new _Controller();
        app.log(true, 'Router.controller', this.controller);
    }

    return Router;

});