define("plugin/shop/site/js/router", [
    'default/js/lib/sandbox',
    'cmn_jquery',
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'default/js/lib/cache',
    'default/js/lib/auth',
    'plugin/shop/site/js/model/order',
    'plugin/shop/site/js/view/siteMenu',
    'plugin/shop/site/js/view/siteWidgets',
    'plugin/shop/common/js/collection/settings'
], function (Sandbox, $, _, Backbone, Cache, Auth, SiteOrder, SiteMenu, SiteWidgets, SiteSettings) {

    var order = new SiteOrder({
        ID: "temp"
    });
    var settings = new SiteSettings();

    // why it's here?
    order.url = APP.getApiLink({
        source: 'shop',
        fn: 'order'
    });

    SiteMenu({
        order: order
    });

    SiteWidgets({
        order: order
    });

    order.fetch();
    settings.fetch();

    var Router = Backbone.Router.extend({
        routes: {
            "shop": "home",
            "shop/catalog/:category": "shop_catalog_category",
            "shop/catalog/": "shop_catalog",
            "shop/product/:product": "shop_product",
            "shop/cart": "shop_cart",
            "shop/wishlist": "shop_wishlist",
            "shop/compare": "shop_compare",
            "shop/tracking(/)(:id)": "shop_tracking",
            "shop/profile/orders": "shop_profile_orders"
        },

        initialize: function () {

            var self = this;

            Sandbox.eventSubscribe('global:page:index', function () {
                self.home();
            });

            Sandbox.eventSubscribe('plugin:shop:offers:get', function () {
                self.offers();
            });
        },

        offers: function () {
            // Sandbox.eventNotify('global:content:render', {
            //     name: 'ShopOffers',
            //     el: $('<h1>Offers Goes here</h1>')
            // });
        },

        home: function () {

            Sandbox.eventNotify('global:breadcrumb:show', {
                source: 'shop',
                fn: 'location',
                productID: null,
                categoryID: null
            });

            require(['plugin/shop/site/js/view/listProductLatest'], function (ListProductLatest) {
                // create new view
                var listProductLatest = new ListProductLatest();
                // Site.placeholders.shop.productListOverview.html(listProductLatest.el);
                // debugger;
                listProductLatest.collection.fetch({
                    reset: true
                });

                Sandbox.eventNotify('global:content:render', {
                    name: 'CommonBodyCenter',
                    el: listProductLatest.el
                });
            });

        },

        shop_catalog_category: function (categoryID) {

            // debugger;
            Sandbox.eventNotify('global:breadcrumb:show', {
                source: 'shop',
                fn: 'location',
                productID: null,
                categoryID: categoryID
            });

            require(['plugin/shop/site/js/view/listProductCatalog'], function (ListProductCatalog) {
                // create new view
                var listProductCatalog = new ListProductCatalog({
                    categoryID: categoryID
                });
                // Site.placeholders.shop.productListCatalog.html(listProductCatalog.el);
                listProductCatalog.collection.fetch({
                    reset: true
                });

                Sandbox.eventNotify('global:content:render', {
                    name: 'CommonBodyCenter',
                    el: listProductCatalog.el
                });
            });
        },

        shop_catalog: function (categoryID) {

            Sandbox.eventNotify('global:breadcrumb:show', {
                source: 'shop',
                fn: 'location',
                productID: null,
                categoryID: categoryID
            });

        },

        shop_product: function (productID) {

            Sandbox.eventNotify('global:breadcrumb:show', {
                source: 'shop',
                fn: 'location',
                productID: productID,
                categoryID: null
            });

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
            Sandbox.eventNotify('global:breadcrumb:show', {
                source: 'shop',
                fn: 'location',
                productID: null,
                categoryID: null
            });

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
            Sandbox.eventNotify('global:breadcrumb:show', {
                source: 'shop',
                fn: 'location',
                productID: null,
                categoryID: null
            });

            require(['plugin/shop/site/js/view/cartStandalone'], function (CartStandalone) {
                // debugger;
                var _accountModel = Cache.getObject('account:model');
                Sandbox.eventSubscribe('plugin:account:model:change', function (accountModel) {
                    // debugger;
                    if (accountModel.has('ID'))
                        order.set('account', accountModel.toJSON());
                    else
                        order.unset('account');
                });
                if (_accountModel && _accountModel.has('ID'))
                    order.set('account', _accountModel.toJSON());
                // create new view
                var cartStandalone = new CartStandalone({
                    model: order,
                    settings: settings
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
            Sandbox.eventNotify('global:breadcrumb:show', {
                source: 'shop',
                fn: 'location',
                productID: null,
                categoryID: null
            });

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
            Sandbox.eventNotify('global:breadcrumb:show', {
                source: 'shop',
                fn: 'location',
                productID: null,
                categoryID: null
            });

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

            if (!Site.hasPlugin('account') || !Auth.getAccountID()) {
                Backbone.history.navigate("", true);
                return;
            }

            Sandbox.eventNotify('global:breadcrumb:show', {
                source: 'shop',
                fn: 'location',
                productID: null,
                categoryID: null
            });

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

    });

    return Router;

});