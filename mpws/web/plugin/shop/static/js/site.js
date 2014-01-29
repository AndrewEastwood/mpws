define("plugin/shop/js/site", [
    'cmn_jquery',
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'default/js/lib/cache',
    // views + models
    'default/js/view/pageNavigation',
    'default/js/view/breadcrumb',
    // pages
    // 'plugin/shop/js/view/pageShopHome',
    // 'plugin/shop/js/view/pageShopCatalog',
    // 'plugin/shop/js/view/pageProductEntryStandalone'
], function ($, _, Backbone, Cache, PageNavigation, ViewBreadcrumb) {

    var placeholders = {
        header: $('.MPWSPageHeader'),
        body: $('.MPWSPageBody'),
        footer: $('.MPWSPageFooter')
    }

    // create & configure permanent views
    var _views = {
        navigation: new PageNavigation({
            el: placeholders.menu
        }),
        breadcrumb: new ViewBreadcrumb({
            el: options.breadcrumb
        })
    }

    // var _navigation = new PageNavigation({
    //     el: placeholders.menu
    // });

    // var _breadcrumb = new ViewBreadcrumb({
    //     el: options.breadcrumb
    // });

    _breadcrumb.model.configure({
        source: 'shop:shop_location'
    });

    _navigation.model.configure({
        source: 'shop:shop_location'
    });

    var Router = Backbone.Router.extend({
        routes: {
            "": "home",
            "shop": "home",
            "shop/catalog/:category": "shop_catalog_category",
            "shop/catalog/": "shop_catalog",
            "shop/product/:product": "shop_product",
            "shop/wizard": "shop_wizard",
            "shop/cart": "shop_cart_view",
        },

        initialize: function (options) {

        },

        home: function () {

        },

        shop_catalog_category: function () {

        },

        shop_catalog: function () {

        },

        shop_product: function () {

        },

        shop_wizard: function () {

        },

        shop_cart_view: function () {

        }

    });

    return {
        initRouter: function (customerConfig) {
            return new Router(customerConfig);
        }
    }


    // start site routing
    //var view = null;


    function Router (options) {

        placeholders = _.extend({}, placeholders, options.placeholders);

        // var _pages = {};

        _navigation.render();
        _breadcrumb.render();

        // initialize: function (options) {
        //     // extend parent
        //     MView.prototype.initialize.call(this, options);
        // },

        // renderLocation: function (productID, categoryID) {
        //     app.log(true, 'Breadcrumb view: renderLocation', productID, categoryID);
        //     this.model.setUrlData({
        //         productId: productID || null,
        //         categoryId: categoryID || null
        //     });
        // }


        // debugger;

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


            // require(
            //     [
            //         'qb_center/js/views/authLogin',
            //     ],
            //     function (ViewAuthLogin) {

                    // var viewAuthLogin = appCachedViews.getObject('ViewAuthLogin', function () {
                    //     var _viewAuth = new ViewAuthLogin({
                    //         el: _placeholders.contentMiddle
                    //     });
                    //     _viewAuth.on('auth:ok', function (){
                    //         _self.trigger('app:isActive');
                    //     });
                    //     return _viewAuth;
                    // });

                    // _menu.refresh();
                    // _self.doCleanupPlaceholders();
                    // viewAuthLogin.render();
                //     }
                // );


            // appCachedViews.get('plugin/shop/js/view/pageShopHome')
            // // this is the same as shop_home
            // // so that's why we're using shop_home
            // appCachedRequests.getView(
            //     // path to view
            //     ['plugin/shop/js/view/pageShopHome'],
            //     // setup function (will be called once per requrest)
            //     // the retunr value will be stored into the cache
            //     function (pageShopHome) {
            //         return new pageShopHome(options);
            //     },
            //     // this function will be called right after setup function
            //     // and every time when you reques this view
            //     function (pageShopHome) {
            //         // app.log('every time call')
            //         pageShopHome.render();
            //     }
            // );
        };
        _routeDispatchers.shop_catalog = function (categoryId) {
            // appCachedRequests.getView(
            //     // path to view
            //     'plugin/shop/js/view/pageShopCatalog',
            //     // setup function (will be called once per requrest)
            //     // the retunr value will be stored into the cache
            //     function (pageShopCatalog) {
            //         return new pageShopCatalog(options);
            //     },
            //     // this function will be called right after setup function
            //     // and every time when you reques this view
            //     function (pageShopCatalog) {
            //         // app.log('every time call')
            //         pageShopCatalog.render(categoryId);
            //     }
            // );
        };
        _routeDispatchers.shop_catalog_category = function (categoryId) {
            // app.log(true, '_routeDispatchers.shop_catalog_category', categoryId);
            _routeDispatchers.shop_catalog(categoryId);
        };
        // display particular product
        _routeDispatchers.shop_product = function (productId) {
            // appCachedRequests.getView(
            //     // path to view
            //     'plugin/shop/js/view/pageProductEntryStandalone',
            //     // setup function (will be called once per requrest)
            //     // the retunr value will be stored into the cache
            //     function (pageProductEntryStandalone) {
            //         return new pageProductEntryStandalone(options);
            //     },
            //     // this function will be called right after setup function
            //     // and every time when you reques this view
            //     function (pageProductEntryStandalone) {
            //         // app.log('every time call')
            //         pageProductEntryStandalone.render(productId);
            //     }
            // );
        };
        _routeDispatchers.shop_wizard = function () {};

        // app.log(true, 'plugin/shop/router/shop creating new instance', _.invert(this.navMap));
        var _Controller = Backbone.Router.extend(_routeDispatchers);

        this.controller = new _Controller();
        // app.log(true, 'Router.controller', this.controller);

        // window.shop = this;

        // this.refreshPage = function () {
        //     Backbone.history.loadUrl();
        // }

        // this.isRouteActive = function (routePath) {
        //     return Backbone.history.fragment === routePath;
        // }

        // this.redirectOrRefreshPage = function (routePath) {
        //     if (Backbone.history.fragment === routePath)
        //         self.refreshPage();
        //     else
        //         self.controller.navigate(routePath, {trigger: true});
        // }
    }

    return Router;

});