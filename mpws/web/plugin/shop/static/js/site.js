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
                var listProductLatest = Cache.getObject('ListProductLatest', function () {
                    return new ListProductLatest();
                });
                Site.setPlaceholder('productListOverview', listProductLatest.el);
                listProductLatest.fetchAndRender();
            });

        },

        shop_catalog_category: function (categoryID) {

            Site.showBreadcrumbLocation({
                source: 'shop',
                fn: 'shop_location',
                productID: null,
                categoryID: categoryID
            });

            require(['plugin/shop/js/view/listProductCatalog'], function (ListProductCatalog) {
                // debugger;
                var listProductCatalog = Cache.getObject('ListProductCatalog', function () {
                    var _view = new ListProductCatalog();
                    Site.setPlaceholder('productListCatalog', _view.el);
                    return _view;
                });
                listProductCatalog.fetchAndRender({
                    categoryID: categoryID
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
                var productItemFull = Cache.getObject('ProductItemFull', function () {
                    return new ProductItemFull();
                });
                Site.setPlaceholder('productEntryStandalone', productItemFull.el);
                productItemFull.fetchAndRender({
                    source: 'shop',
                    fn: 'shop_product_item',
                    productID: productID
                });
            });
        },

        shop_wizard: function () {

        },

        shop_cart_view: function () {

        }

    });

    return Router;

});