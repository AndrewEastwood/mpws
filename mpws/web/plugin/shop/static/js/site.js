define("plugin/shop/js/site", [
    'default/js/lib/sandbox',
    'customer/js/site',
    'cmn_jquery',
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'default/js/lib/cache',
    'plugin/shop/js/view/siteMenu',
    'plugin/shop/js/view/siteWidgets',
], function (Sandbox, Site, $, _, Backbone, Cache, SiteMenu, SiteWidgets) {

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

            Sandbox.eventSubscribe('site:page:index', function () {
                self.home();
            });

        },

        home: function () {

            Sandbox.eventNotify('site:breadcrumb:show', {
                source: 'shop',
                fn: 'shop_location',
                productID: null,
                categoryID: null
            });

            require(['plugin/shop/js/view/listProductLatest'], function (ListProductLatest) {
                // using this wrapper to cleanup previous view and create new one
                Cache.withObject('ListProductLatest', function (cachedView) {
                    // debugger;
                    // remove previous view
                    if (cachedView && cachedView.remove)
                        cachedView.remove();

                    // create new view
                    var listProductLatest = new ListProductLatest();
                    Site.placeholders.shop.productListOverview.html(listProductLatest.el);
                    listProductLatest.fetchAndRender();

                    // return view object to pass it into this function at next invocation
                    return listProductLatest;
                });
            });

        },

        shop_catalog_category: function (categoryID) {

            // debugger;
            Sandbox.eventNotify('site:breadcrumb:show', {
                source: 'shop',
                fn: 'shop_location',
                productID: null,
                categoryID: categoryID
            });

            // debugger;
            require(['plugin/shop/js/view/listProductCatalog'], function (ListProductCatalog) {
                // using this wrapper to cleanup previous view and create new one
                Cache.withObject('ListProductCatalog', function (cachedView) {
                    // remove previous view
                    if (cachedView && cachedView.remove)
                        cachedView.remove();

                    // create new view
                    var listProductCatalog = new ListProductCatalog({
                        categoryID: categoryID
                    });
                    Site.placeholders.shop.productListCatalog.html(listProductCatalog.el);
                    listProductCatalog.fetchAndRender();

                    // return view object to pass it into this function at next invocation
                    return listProductCatalog;
                });
            });
        },

        shop_catalog: function (categoryID) {

            Sandbox.eventNotify('site:breadcrumb:show', {
                source: 'shop',
                fn: 'shop_location',
                productID: null,
                categoryID: categoryID
            });

        },

        shop_product: function (productID) {

            Sandbox.eventNotify('site:breadcrumb:show', {
                source: 'shop',
                fn: 'shop_location',
                productID: productID,
                categoryID: null
            });

            require(['plugin/shop/js/view/productItemFull'], function (ProductItemFull) {

                // using this wrapper to cleanup previous view and create new one
                Cache.withObject('ProductItemFull', function (cachedView) {
                    // remove previous view
                    if (cachedView && cachedView.remove)
                        cachedView.remove();

                    // create new view
                    var productItemFull = new ProductItemFull();
                    Site.placeholders.shop.productItemStandalone.html(productItemFull.el);

                    productItemFull.fetchAndRender({
                        productID: productID
                    });

                    // return view object to pass it into this function at next invocation
                    return productItemFull;
                });
            });
        },

        shop_compare: function () {
            Sandbox.eventNotify('site:breadcrumb:show', {
                source: 'shop',
                fn: 'shop_location',
                productID: null,
                categoryID: null
            });

            require(['plugin/shop/js/view/productsCompare'], function (ProductsCompare) {
                Cache.withObject('ProductsCompare', function (cachedView) {
                    // remove previous view
                    if (cachedView && cachedView.remove)
                        cachedView.remove();

                    // create new view
                    var productsCompare = new ProductsCompare();
                    Site.placeholders.shop.productCompare.html(productsCompare.$el);
                    // debugger;
                    productsCompare.fetchAndRender({
                        action: "INFO"
                    });

                    // return view object to pass it into this function at next invocation
                    return productsCompare;
                });
            });
        },

        shop_cart: function () {
            Sandbox.eventNotify('site:breadcrumb:show', {
                source: 'shop',
                fn: 'shop_location',
                productID: null,
                categoryID: null
            });

            require(['plugin/shop/js/view/cartStandalone'], function (CartStandalone) {
                Cache.withObject('CartStandalone', function (cachedView) {
                    // remove previous view
                    if (cachedView && cachedView.remove)
                        cachedView.remove();

                    // create new view
                    var cartStandalone = new CartStandalone();
                    Site.placeholders.shop.shoppingCartStandalone.html(cartStandalone.$el);
                    cartStandalone.fetchAndRender({
                        action: "INFO"
                    });

                    // return view object to pass it into this function at next invocation
                    return cartStandalone;
                });
            });
        },

        shop_wishlist: function () {
            Sandbox.eventNotify('site:breadcrumb:show', {
                source: 'shop',
                fn: 'shop_location',
                productID: null,
                categoryID: null
            });

            require(['plugin/shop/js/view/wishListStandalone'], function (WishListStandalone) {
                Cache.withObject('WishListStandalone', function (cachedView) {
                    // remove previous view
                    if (cachedView && cachedView.remove)
                        cachedView.remove();

                    // create new view
                    var wishListStandalone = new WishListStandalone();
                    Site.placeholders.shop.shoppingWishListStandalone.html(wishListStandalone.$el);
                    wishListStandalone.fetchAndRender({
                        action: "INFO"
                    });

                    // return view object to pass it into this function at next invocation
                    return wishListStandalone;
                });
            });
        },

        shop_tracking: function (orderHash) {
            Sandbox.eventNotify('site:breadcrumb:show', {
                source: 'shop',
                fn: 'shop_location',
                productID: null,
                categoryID: null
            });

            require(['plugin/shop/js/view/trackingStatus'], function (TrackingStatus) {
                Cache.withObject('TrackingStatus', function (cachedView) {
                    // debugger;
                    // remove previous view
                    if (cachedView && cachedView.remove)
                        cachedView.remove();

                    // create new view
                    var trackingStatus = new TrackingStatus();
                    Site.placeholders.shop.ordertrackingStandalone.html(trackingStatus.$el);
                    if (orderHash)
                        trackingStatus.fetchAndRender({
                            orderHash: orderHash
                        });
                    else
                        trackingStatus.fetchAndRender();

                    // return view object to pass it into this function at next invocation
                    return trackingStatus;
                });
            });
        },

        //
        shop_profile_orders: function () {

            if (!Site.hasPlugin('account')) {
                Backbone.history.navigate("", {trigger: true});
                return;
            }

            if (!Cache.hasObject('AccountProfileID')) {
                Backbone.history.navigate("", {trigger: true});
                return;
            }

            Sandbox.eventNotify('site:breadcrumb:show', {
                source: 'shop',
                fn: 'shop_location',
                productID: null,
                categoryID: null
            });

            require(['plugin/shop/js/view/profileOrders'], function (ProfileOrders) {
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

                    Sandbox.eventNotify('account:profile:show', profileOrders.$el);

                    // return view object to pass it into this function at next invocation
                    return profileOrders;
                });
            });
        }

    });

    return Router;

});