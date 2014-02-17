define("plugin/shop/js/site", [
    'customer/js/site',
    'cmn_jquery',
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'default/js/lib/cache',
    'plugin/shop/js/view/catalogStructureMenu',
    'plugin/shop/js/model/cart',
    'plugin/shop/js/view/cartEmbedded'
], function (Site, $, _, Backbone, Cache, CatalogStructureMenu, ModelCart, CartEmbedded) {

    var shoppingCartModel = new ModelCart();

    var Router = Backbone.Router.extend({
        routes: {
            "": "home",
            "shop": "home",
            "shop/catalog/:category": "shop_catalog_category",
            "shop/catalog/": "shop_catalog",
            "shop/product/:product": "shop_product",
            "shop/wizard": "shop_wizard",
            "shop/cart": "shop_cart",
        },

        initialize: function () {

            // inject shop menu (category menu)
            var shopMenuItem = new CatalogStructureMenu();
            Site.addMenuItem(shopMenuItem.$el);
            shopMenuItem.fetchAndRender();

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
                    // remove previous view
                    if (cachedView && cachedView.destroy)
                        cachedView.destroy();

                    // create new view
                    var listProductLatest = new ListProductLatest();
                    Site.setPlaceholder('productListOverview', listProductLatest.el);
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
                    Site.setPlaceholder('productListCatalog', listProductCatalog.el);

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
                    Site.setPlaceholder('productEntryStandalone', productItemFull.el);

                    productItemFull.fetchAndRender({
                        source: 'shop',
                        fn: 'shop_product_item',
                        productID: productID
                    });

                    // return view object to pass it into this function at next invocation
                    return productItemFull;
                });
            });
        },

        shop_wizard: function () {

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
                    Site.setPlaceholder('shoppingCartStandalone', cartStandalone.$el);
                    cartStandalone.fetchAndRender();

                    // return view object to pass it into this function at next invocation
                    return cartStandalone;
                });
            });
        }

    });

    return Router;

});