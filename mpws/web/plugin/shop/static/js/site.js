define("plugin/shop/js/site", [
    'customer/js/site',
    'cmn_jquery',
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'default/js/lib/cache',
    'plugin/shop/js/view/menuSite',
    'plugin/shop/js/model/productsCompare',
    'plugin/shop/js/model/wishList',
    'plugin/shop/js/model/cart',
    'plugin/shop/js/view/cartEmbedded'
], function (Site, $, _, Backbone, Cache, MenuSite, ModelProductsCompare, ModelWishList, ModelCart, CartEmbedded) {

    var shoppingCartModel = new ModelCart();
    var shoppingWishListModel = new ModelWishList();
    var productsCompareModel = new ModelProductsCompare();

    var Router = Backbone.Router.extend({
        routes: {
            "": "home",
            "shop": "home",
            "shop/catalog/:category": "shop_catalog_category",
            "shop/catalog/": "shop_catalog",
            "shop/product/:product": "shop_product",
            "shop/wizard": "shop_wizard",
            "shop/cart": "shop_cart",
            "shop/wishlist": "shop_wishlist",
            "shop/compare": "shop_compare",
        },

        initialize: function () {

            MenuSite.render();

            // inject embedded shopping cart
            var cartEmbedded = new CartEmbedded({
                model: shoppingCartModel
            });
            Site.addWidgetTop(cartEmbedded.$el);
            cartEmbedded.fetchAndRender();
        },

        home: function () {

            Site.showBreadcrumbLocation({
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
                    if (cachedView && cachedView.destroy)
                        cachedView.destroy();

                    // create new view
                    var listProductLatest = new ListProductLatest();
                    Site.setPlaceholder('bodyCenter', listProductLatest.el);
                    listProductLatest.fetchAndRender();

                    // return view object to pass it into this function at next invocation
                    return listProductLatest;
                });
            });

        },

        shop_catalog_category: function (categoryID) {

            // debugger;
            Site.showBreadcrumbLocation({
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
                    if (cachedView && cachedView.destroy)
                        cachedView.destroy();

                    // create new view
                    var listProductCatalog = new ListProductCatalog();
                    Site.setPlaceholder('bodyCenter', listProductCatalog.el);

                    listProductCatalog.fetchAndRender({
                        categoryID: categoryID
                    });

                    // return view object to pass it into this function at next invocation
                    return listProductCatalog;
                });
            });
        },

        shop_catalog: function (categoryID) {

            Site.showBreadcrumbLocation({
                source: 'shop',
                fn: 'shop_location',
                productID: null,
                categoryID: categoryID
            });

        },

        shop_product: function (productID) {

            Site.showBreadcrumbLocation({
                source: 'shop',
                fn: 'shop_location',
                productID: productID,
                categoryID: null
            });

            require(['plugin/shop/js/view/productItemFull'], function (ProductItemFull) {

                // using this wrapper to cleanup previous view and create new one
                Cache.withObject('ProductItemFull', function (cachedView) {
                    // remove previous view
                    if (cachedView && cachedView.destroy)
                        cachedView.destroy();

                    // create new view
                    var productItemFull = new ProductItemFull();
                    Site.setPlaceholder('bodyCenter', productItemFull.el);

                    productItemFull.fetchAndRender({
                        productID: productID
                    });

                    // return view object to pass it into this function at next invocation
                    return productItemFull;
                });
            });
        },

        shop_compare: function () {
            Site.showBreadcrumbLocation({
                source: 'shop',
                fn: 'shop_location',
                productID: null,
                categoryID: null
            });

            require(['plugin/shop/js/view/productsCompare'], function (ProductsCompare) {
                Cache.withObject('ProductsCompare', function (cachedView) {
                    // remove previous view
                    if (cachedView && cachedView.destroy)
                        cachedView.destroy();

                    // create new view
                    var productsCompare = new ProductsCompare({
                        model: productsCompareModel
                    });
                    Site.setPlaceholder('bodyCenter', productsCompare.$el);
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
            Site.showBreadcrumbLocation({
                source: 'shop',
                fn: 'shop_location',
                productID: null,
                categoryID: null
            });

            require(['plugin/shop/js/view/cartStandalone'], function (CartStandalone) {
                Cache.withObject('CartStandalone', function (cachedView) {
                    // remove previous view
                    if (cachedView && cachedView.destroy)
                        cachedView.destroy();

                    // create new view
                    var cartStandalone = new CartStandalone({
                        model: shoppingCartModel
                    });
                    Site.setPlaceholder('bodyCenter', cartStandalone.$el);
                    cartStandalone.fetchAndRender({
                        action: "INFO"
                    });

                    // return view object to pass it into this function at next invocation
                    return cartStandalone;
                });
            });
        },

        shop_wishlist: function () {
            Site.showBreadcrumbLocation({
                source: 'shop',
                fn: 'shop_location',
                productID: null,
                categoryID: null
            });

            require(['plugin/shop/js/view/wishListStandalone'], function (WishListStandalone) {
                Cache.withObject('WishListStandalone', function (cachedView) {
                    // remove previous view
                    if (cachedView && cachedView.destroy)
                        cachedView.destroy();

                    // create new view
                    var wishListStandalone = new WishListStandalone({
                        model: shoppingWishListModel
                    });
                    Site.setPlaceholder('bodyCenter', wishListStandalone.$el);
                    wishListStandalone.fetchAndRender({
                        action: "INFO"
                    });

                    // return view object to pass it into this function at next invocation
                    return wishListStandalone;
                });
            });
        }

    });

    return Router;

});