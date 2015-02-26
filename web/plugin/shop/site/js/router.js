define("plugin/shop/site/js/router", [
    'default/js/lib/sandbox',
    'jquery',
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'default/js/lib/cache',
    'default/js/lib/auth',
    'plugin/shop/site/js/model/order',
    'plugin/shop/site/js/view/siteMenu',
    'plugin/shop/site/js/view/siteWidgets',
    'plugin/shop/common/js/model/setting'
], function (Sandbox, $, _, Backbone, Cache, Auth, SiteOrder, SiteMenu, SiteWidgets, SiteSettings) {

    var order = new SiteOrder({
        ID: "temp"
    });
    var settings = new SiteSettings();

    // why it's here?
    order.url = APP.getApiLink({
        source: 'shop',
        fn: 'orders'
    });

    var $dfdSettings = settings.fetch();

    var routes = {
        "!/shop": "home",
        "!/shop/catalog/:category": "shop_catalog_category",
        "!/shop/catalog/:category/:page": "shop_catalog_category_page",
        "!/shop/catalog/": "shop_catalog",
        "!/shop/product/:product": "shop_product",
        "!/shop/cart": "shop_cart",
        "!/shop/wishlist": "shop_wishlist",
        "!/shop/compare": "shop_compare",
        "!/shop/tracking/(:id)": "shop_tracking",
        "!/shop/profile/orders": "shop_profile_orders"
    };

    var Router = Backbone.Router.extend({

        name: "shop",

        settings: null,

        routes: routes,

        urls: _(routes).invert(),

        initialize: function () {

            var self = this;

            Backbone.on('appinstance:added', function (key) {
                if (key !== self.name) {
                    return;
                }

                SiteMenu({
                    order: order
                });

                SiteWidgets({
                    order: order
                });

                order.fetch();
            });


            Sandbox.eventSubscribe('global:page:index', function () {
                self.home();
            });

            // Sandbox.eventSubscribe('plugin:shop:offers:get', function () {
            //     self.offers();
            // });
        },

        offers: function () {
            // Sandbox.eventNotify('global:content:render', {
            //     name: 'ShopOffers',
            //     el: $('<h1>Offers Goes here</h1>')
            // });
        },

        home: function () {
            APP.getCustomer().setBreadcrumb();
            require(['plugin/shop/site/js/view/home'], function (PageHome) {
                var pageHome = new PageHome();
                pageHome.render();
                Sandbox.eventNotify('global:content:render', {
                    name: 'CommonBodyCenter',
                    el: pageHome.el
                });
            });

        },

        shop_catalog_category: function (categoryID) {
            require(['plugin/shop/site/js/view/listProductCatalog'], function (ListProductCatalog) {
                // create new view
                var listProductCatalog = new ListProductCatalog({
                    categoryID: categoryID
                });
                listProductCatalog.collection.fetch({
                    reset: true
                });

                Sandbox.eventNotify('global:content:render', {
                    name: 'CommonBodyCenter',
                    el: listProductCatalog.el
                });
            });
        },

        shop_catalog_category_page: function (categoryID, pageNo) {
            require(['plugin/shop/site/js/view/listProductCatalog'], function (ListProductCatalog) {
                // create new view
                var listProductCatalog = new ListProductCatalog({
                    categoryID: categoryID
                });
                var _pageNo = parseInt(pageNo, 10);
                if (_pageNo.toString() === pageNo) {
                    listProductCatalog.collection.setFilter('filter_viewPageNum', pageNo);
                }
                listProductCatalog.collection.fetch({
                    reset: true
                });

                Sandbox.eventNotify('global:content:render', {
                    name: 'CommonBodyCenter',
                    el: listProductCatalog.el
                });
            });
        },

        shop_product: function (productID) {
            require(['plugin/shop/site/js/view/productItemFull'], function (ViewProductItemFull) {
                // create new view
                var viewProductItemFull = new ViewProductItemFull({
                    productID: productID
                });
                viewProductItemFull.model.fetch();
                Sandbox.eventNotify('global:content:render', {
                    name: 'CommonBodyCenter',
                    el: viewProductItemFull.el
                });
            });
        },

        shop_compare: function () {
            APP.getCustomer().setBreadcrumb();
            require(['plugin/shop/site/js/view/listProductCompare'], function (ListProductCompare) {
                // create new view
                var listProductCompare = new ListProductCompare();
                listProductCompare.render();
                Sandbox.eventNotify('global:content:render', {
                    name: 'CommonBodyCenter',
                    el: listProductCompare.el
                });
            });
        },

        shop_cart: function () {
            APP.getCustomer().setBreadcrumb();
            require(['plugin/shop/site/js/view/cartStandalone'], function (CartStandalone) {
                // debugger;
                var plgAccount = APP.instances.account;
                var accountModel = null;
                if (plgAccount) {
                    accountModel = plgAccount.constructor.account;
                    accountModel.on('change', function () {
                        console.log('account model changed');
                        if (accountModel.has('ID'))
                            order.set('account', accountModel.toJSON());
                        else
                            order.unset('account');
                    });
                }
                // var accountModel = Cache.getObject('account:model');
                // Sandbox.eventSubscribe('plugin:account:model:change', function (accountModel) {
                //     // debugger;
                //     if (accountModel.has('ID'))
                //         order.set('account', accountModel.toJSON());
                //     else
                //         order.unset('account');
                // });
                if (accountModel && accountModel.has('ID')) {
                    console.log('account model has data');
                    order.set('account', accountModel.toJSON());
                }
                // create new view
                var cartStandalone = new CartStandalone({
                    model: order
                });
                // cartStandalone.collection.fetch({merge:true});
                cartStandalone.render();
                Sandbox.eventNotify('global:content:render', {
                    name: 'CommonBodyCenter',
                    el: cartStandalone.el
                });
            });
        },

        shop_wishlist: function () {
            APP.getCustomer().setBreadcrumb();
            require(['plugin/shop/site/js/view/listProductWish'], function (ListProductWish) {
                // create new view
                var listProductWish = new ListProductWish();
                listProductWish.render();
                Sandbox.eventNotify('global:content:render', {
                    name: 'CommonBodyCenter',
                    el: listProductWish.el
                });
            });
        },

        shop_tracking: function (orderHash) {
            APP.getCustomer().setBreadcrumb();
            require(['plugin/shop/site/js/view/trackingStatus'], function (TrackingStatus) {
                // create new view
                var trackingStatus = new TrackingStatus();
                trackingStatus.setOrderHash(orderHash);
                Sandbox.eventNotify('global:content:render', {
                    name: 'CommonBodyCenter',
                    el: trackingStatus.el
                });
            });
        },

        //
        shop_profile_orders: function () {
            APP.getCustomer().setBreadcrumb();
            if (!Site.hasPlugin('account') || !Auth.getUserID()) {
                Backbone.history.navigate("", true);
                return;
            }
            require(['plugin/shop/site/js/view/profileOrders'], function (ProfileOrders) {
                // Cache.withObject('ProfileOrders', function (cachedView) {
                // debugger;
                // remove previous view
                // if (cachedView && cachedView.remove)
                //     cachedView.remove();

                // create new view
                var profileOrders = new ProfileOrders();
                // view.setPagePlaceholder(profileOrders.$el);
                profileOrders.fetchAndRender({
                    profileID: Cache.getObject('AccountProfileID')
                });

                Sandbox.eventNotify('plugin:account:profile:show', profileOrders.$el);

                // return view object to pass it into this function at next invocation
                // return profileOrders;
                // });
            });
        }

    }, {
        preload: function (callback) {
            $dfdSettings.done(function () {
                var _s = settings.toSettings();
                Router.prototype.settings = _s;
                Router.prototype.settings._user = {
                    activeCurrency: SiteWidgets.ExchangeRates.getActiveCurrencyName(_s.MISC.SiteDefaultPriceCurrencyType && _s.MISC.SiteDefaultPriceCurrencyType, !!_s.MISC.ShowSiteCurrencySelector)
                }
                // console.log('shop settings ready: calling callback');
                callback();

                Backbone.on('changed:plugin-shop-currency', function (currencyName) {
                    Router.prototype.settings._user.activeCurrency = currencyName;
                });
            });
        }
    });

    return Router;
});