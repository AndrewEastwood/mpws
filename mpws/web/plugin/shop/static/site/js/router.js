define("plugin/shop/site/js/router", [
    'default/js/lib/sandbox',
    'cmn_jquery',
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'default/js/lib/cache',
    'plugin/shop/site/js/view/siteMenu',
    'plugin/shop/site/js/view/siteWidgets',
], function (Sandbox, $, _, Backbone, Cache, SiteMenu, SiteWidgets) {

    var Router = Backbone.Router.extend({
        routes: {
            "shop": "home",
            "shop/catalog/:category": "shop_catalog_category",
            "shop/catalog/": "shop_catalog",
            "shop/product/:product": "shop_product",
            "shop/wizard": "shop_wizard",
            "shop/cart": "shop_cart",
            "shop/wishlist": "shop_wishlist",
            "shop/compare": "shop_compare",
            "shop/tracking(/)(:id)": "shop_tracking",
            "shop/profile/orders": "shop_profile_orders"
        },

        initialize: function () {

            var self = this;

            // SiteMenu.render();

            // SiteWidgets.render();

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
                listProductLatest.collection.fetch();

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

            // debugger;
            require(['plugin/shop/site/js/view/listProductCatalog'], function (ListProductCatalog) {
                // using this wrapper to cleanup previous view and create new one
                Cache.withObject('ListProductCatalog', function (cachedView) {
                    // remove previous view
                    if (cachedView && cachedView.remove)
                        cachedView.remove();

                    // create new view
                    var listProductCatalog = new ListProductCatalog();
                    // Site.placeholders.shop.productListCatalog.html(listProductCatalog.el);
                    listProductCatalog.collection.fetch({
                        categoryID: categoryID
                    });

                    Sandbox.eventNotify('global:content:render', {
                        name: 'CommonBodyCenter',
                        el: listProductCatalog.el
                    });

                    // return view object to pass it into this function at next invocation
                    return listProductCatalog;
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
                    id: productID
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

            require(['plugin/shop/site/js/view/productsCompare'], function (ProductsCompare) {
                // Cache.withObject('ProductsCompare', function (cachedView) {
                //     // remove previous view
                //     if (cachedView && cachedView.remove)
                //         cachedView.remove();

                    // create new view
                    var productsCompare = new ProductsCompare();
                    // Site.placeholders.shop.productCompare.html(productsCompare.$el);
                    // debugger;
                    productsCompare.fetchAndRender({
                        action: "INFO"
                    });

                    Sandbox.eventNotify('global:content:render', {
                        name: 'CommonBodyCenter',
                        el: productsCompare.el
                    });
                    // return view object to pass it into this function at next invocation
                //     return productsCompare;
                // });
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
                Cache.withObject('CartStandalone', function (cachedView) {
                    // remove previous view
                    if (cachedView && cachedView.remove)
                        cachedView.remove();

                    // create new view
                    var cartStandalone = new CartStandalone();
                    // Site.placeholders.shop.shoppingCartStandalone.html(cartStandalone.$el);
                    cartStandalone.fetchAndRender({
                        action: "INFO"
                    });
                    Sandbox.eventNotify('global:content:render', {
                        name: 'ShopCartStandalone',
                        el: cartStandalone.el
                    });

                    // return view object to pass it into this function at next invocation
                    return cartStandalone;
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

            require(['plugin/shop/site/js/view/wishListStandalone'], function (WishListStandalone) {
                Cache.withObject('WishListStandalone', function (cachedView) {
                    // remove previous view
                    if (cachedView && cachedView.remove)
                        cachedView.remove();

                    // create new view
                    var wishListStandalone = new WishListStandalone();
                    // Site.placeholders.shop.shoppingWishListStandalone.html(wishListStandalone.$el);
                    wishListStandalone.fetchAndRender({
                        action: "INFO"
                    });
                    Sandbox.eventNotify('global:content:render', {
                        name: 'ShopWishList',
                        el: wishListStandalone.el
                    });

                    // return view object to pass it into this function at next invocation
                    return wishListStandalone;
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
                Cache.withObject('TrackingStatus', function (cachedView) {
                    // debugger;
                    // remove previous view
                    if (cachedView && cachedView.remove)
                        cachedView.remove();

                    // create new view
                    var trackingStatus = new TrackingStatus();
                    
                    
                    // Site.placeholders.shop.ordertrackingStandalone.html(trackingStatus.$el);
                    if (orderHash)
                        trackingStatus.fetchAndRender({
                            orderHash: orderHash
                        });
                    else
                        trackingStatus.fetchAndRender();

                    Sandbox.eventNotify('global:content:render', {
                        name: 'ShopOrdertrackingStandalone',
                        el: trackingStatus.el
                    });
                    // return view object to pass it into this function at next invocation
                    return trackingStatus;
                });
            });
        },

        //
        shop_profile_orders: function () {

            // if (!Site.hasPlugin('account')) {
            //     Backbone.history.navigate("", {trigger: true});
            //     return;
            // }

            if (!Cache.hasObject('AccountProfileID')) {
                Backbone.history.navigate("", {trigger: true});
                return;
            }

            Sandbox.eventNotify('global:breadcrumb:show', {
                source: 'shop',
                fn: 'location',
                productID: null,
                categoryID: null
            });

            require(['plugin/shop/site/js/view/profileOrders'], function (ProfileOrders) {
                Cache.withObject('ProfileOrders', function (cachedView) {
                    // debugger;
                    // remove previous view
                    if (cachedView && cachedView.remove)
                        cachedView.remove();

                    // create new view
                    var profileOrders = new ProfileOrders();
                    // view.setPagePlaceholder(profileOrders.$el);
                    profileOrders.fetchAndRender({
                        profileID: Cache.getObject('AccountProfileID')
                    });

                    Sandbox.eventNotify('plugin:account:profile:show', profileOrders.$el);

                    // return view object to pass it into this function at next invocation
                    return profileOrders;
                });
            });
        }

    });

    return Router;

});