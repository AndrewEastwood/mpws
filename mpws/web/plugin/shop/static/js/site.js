define("plugin/shop/js/site", [
    'customer/js/site',
    'cmn_jquery',
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'default/js/lib/cache',
    'plugin/shop/js/view/catalogStructureMenu'
], function (Site, $, _, Backbone, Cache, CatalogStructureMenu) {

    // create & configure permanent views
    var _views = {};

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

        initialize: function () {

            var shopMenuItem = new CatalogStructureMenu();
            Site.addMenuItem(shopMenuItem.$el);
            shopMenuItem.fetchAndRender();

        },

        home: function () {

            Site.showBreadcrumbLocation({
                source: 'shop',
                fn: 'shop_location',
                productID: null,
                categoryID: null
            });

            require(['plugin/shop/js/view/listProductLatest'], function (ListProductLatest) {
                // var listProductLatest = Cache.getObject('ListProductLatest', function () {
                //     return new ListProductLatest();
                // });
                Cache.withObject('ListProductLatest', function (view) {
                    view && view.destroy && view.destroy();
                });
                var listProductLatest = new ListProductLatest();
                Site.setPlaceholder('productListOverview', listProductLatest.el);
                listProductLatest.fetchAndRender();
                Cache.setObject('ListProductLatest', listProductLatest);
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
                // debugger;
                // var listProductCatalog = Cache.getObject('ListProductCatalog', function () {
                //     return new ListProductCatalog();
                // });
                Cache.withObject('ListProductCatalog', function (view) {
                    view && view.destroy && view.destroy();
                });
                var listProductCatalog = new ListProductCatalog();
                Site.setPlaceholder('productListCatalog', listProductCatalog.el);
                listProductCatalog.fetchAndRender({
                    categoryID: categoryID
                });
                Cache.setObject('ListProductCatalog', listProductCatalog);
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

                Cache.withObject('ProductItemFull', function (view) {
                    view && view.destroy && view.destroy();
                });
                // var productItemFull = Cache.getObject('ProductItemFull', function () {
                //     return new ProductItemFull();
                // });
                var productItemFull = new ProductItemFull();
                Site.setPlaceholder('productEntryStandalone', productItemFull.el);
                productItemFull.fetchAndRender({
                    source: 'shop',
                    fn: 'shop_product_item',
                    productID: productID
                });
                Cache.setObject('ProductItemFull', productItemFull);
            });
        },

        shop_wizard: function () {

        },

        shop_cart_view: function () {

        }

    });

    return Router;

});