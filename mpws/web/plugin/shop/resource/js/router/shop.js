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
    }

    Router.prototype.shopHome = function() {
        app.log(true, 'Router.prototype.shopHome');
        this.view.pageShopHome();
    };

    Router.prototype.shopProductsLatest = function(route, name, callback) {
        app.log(true, 'Router.prototype.shopProductsLatest');
        this.view.pageShopProductListLatest(route, name, callback);
    };

    Router.prototype.shopCatalog = function(route, name, callback) {
        this.view.pageShopCatalog(route, name, callback);
    };

    Router.prototype.shopCatalogByCategory = function(route, name, callback) {
        this.view.pageShopCatalogByCategory(route, name, callback);
    };

    Router.prototype.shopCatalogByCategoryAndBrand = function(route, name, callback) {
        this.view.pageShopCatalogByCategoryAndBrand(route, name, callback);
    };
    
    Router.prototype.shopCart = function(route, name, callback) {
        this.view.pageShopCart();
    };

    Router.prototype.shopProductItem = function(productId, name, callback) {
        app.log(true, 'shop_product')
        this.view.pageShopProductItemByID(productId);
    };

    Router.prototype.init = function(productId, name, callback) {
        app.log(true, 'plugin/shop/router/shop constructor');

        // if (this.Controller)
        //  return;
        var self = this;

        app.log(true, 'plugin/shop/router/shop creating new instance');
        var _Controller = Backbone.Router.extend({
            routes: {
                "shop": "shop_home",
                "shop/latest": "shop_latest",
                "shop/catalog": "shop_catalog",
                "shop/catalog/:category": "shop_category",
                "shop/catalog/:category/:brand": "shop_category_brand",
                "shop/product/:product": "shop_product",
                "shop/cart": "shop_cart"
            },
            // shop default page
            shop_home: function () {
                // self.view.pageShopHome();
                self.shopHome();
            },
            // display shop latest stuff
            shop_latest: function (route, name, callback) {
                // self.view.pageShopProductListLatest(route, name, callback);
                self.shopProductsLatest(route, name, callback);
            },
            // display catalog
            shop_catalog: function (route, name, callback) {
                // self.view.pageShopCatalog(route, name, callback);
                self.shopCatalog(route, name, callback);
            },
            // display category
            shop_category: function () {
                // here we handle different stuff:
                // sorting by [name, date, price, popularity, etc]
                // pagination
                // 
                // self.view.pageShopCatalogByCategory(route, name, callback);
                self.shopCatalogByCategory(route, name, callback);
            },
            // display category
            shop_category_brand: function () {
                // here we handle different stuff:
                // sorting by [name, date, price, popularity, etc]
                // pagination
                // 
                // self.view.pageShopCatalogByCategoryAndBrand(route, name, callback);
                self.shopCatalogByCategoryAndBrand(route, name, callback);
            },
            shop_cart: function () {
                // self.view.pageShopCart();
                self.shopCart();
            },
            // display particular product
            shop_product: function (productId, name, callback) {
                app.log(true, 'shop_product', productId);
                // self.view.pageShopProductItemByID(productId);
                self.shopProductItem(productId, name, callback);
            }

        });

        this.controller = new _Controller();

        // init actions
        $('body').on('click', '[data-action]', function () {
            var _action = $(this).data('action');
            var _oid = $(this).data('oid');

            switch (_action) {
                case "shop:buy":
                    self.view.model.shoppingChartAdd(_oid, function (rez) {
                        _libHtml.messageBox('You"re going buy: ' + _oid);
                    });
                    break;
                case "shop:chart:item-remove":
                    self.view.model.shoppingChartRemove(_oid, function (rez) {
                        self.shopCart();
                    });
                    break;
                case "shop:chart:clear":
                    self.view.model.shoppingChartClear(function (rez) {
                        self.shopCart();
                    });
                    break;
            }
        })

        // app.log(true, 'Router.controller', this.Controller);
    }

    return Router;

});