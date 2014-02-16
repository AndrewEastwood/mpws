define("plugin/shop/js/site", [
    'customer/js/site',
    'cmn_jquery',
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'default/js/lib/cache',
    'plugin/shop/js/view/catalogStructureMenu',
    'plugin/shop/js/view/cartEmbedded'
], function (Site, $, _, Backbone, Cache, CatalogStructureMenu, CartEmbedded) {

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

            var cartEmbedded = new CartEmbedded();
            Site.addWidgetTop(cartEmbedded.$el);
            cartEmbedded.fetchAndRender();

            window.cart = cartEmbedded;

            // attach global events (init actions)
            $('body').on('click', '[data-action]', {context: this}, function () {
                var _action = $(this).data('action');
                var _oid = $(this).data('oid');

                switch (_action) {
                    // wishlist
                    case "shop:wishlist:add":
                        break;
                    case "shop:wishlist:remove":
                        break;
                    // compare
                    case "shop:compare:add":
                        break;
                    case "shop:compare:remove":
                        break;

                    // cart
                    case "shop:cart:add":
                        //debugger;
                        cartEmbedded.collection.productAdd(_oid, 1);
                        // self.model.shoppingCartAdd(_oid, function (rez) {
                        //     var _cartEmbeddedRenderConfig = mpwsPageLib.modifyRenderConfig(self.componentsCommon.cartEmbedded, {
                        //         callback: function () {
                        //             self.action_cartEmbeddedShow();
                        //         }
                        //     });
                        //     mpwsPageLib.render(_cartEmbeddedRenderConfig);
                        //     // _libHtml.messageBox('You"re going buy: ' + _oid);
                        // });
                        break;
                    case "shop:cart:embedded-item-remove":
                        // debugger;
                        cartEmbedded.collection.productAdd(_oid, 0);
                        // self.model.shoppingCartRemove(_oid, function (rez) {
                        //     // self.pageShopCart();
                        //     var _cartEmbeddedRenderConfig = mpwsPageLib.modifyRenderConfig(self.componentsCommon.cartEmbedded, {
                        //         callback: function () {
                        //             self.action_cartEmbeddedShow();
                        //         }
                        //     });
                        //     mpwsPageLib.render(_cartEmbeddedRenderConfig);
                        // });
                        break;
                    case "shop:cart:embedded-clear":
                        // self.model.shoppingCartClear(function (rez) {
                        //     // self.pageShopCart();
                        //     var _cartEmbeddedRenderConfig = mpwsPageLib.modifyRenderConfig(self.componentsCommon.cartEmbedded, {
                        //         callback: function () {
                        //             // self.action_cartEmbeddedHide();
                        //             if (self.router.isRouteActive(self.router.navMap.shop_cart_view))
                        //                 self.router.refreshPage();
                        //             if (self.router.isRouteActive(self.router.navMap.shop_cart_checkout_view))
                        //                 self.router.redirectOrRefreshPage(self.router.navMap.shop_cart_view);
                        //         }
                        //     });
                        //     mpwsPageLib.render(_cartEmbeddedRenderConfig);
                        // });
                        break;
                    case "shop:cart:standalone-item-remove":
                        // self.model.shoppingCartRemove(_oid, function (rez) {
                        //     // self.pageShopCart();
                        //     self.router.redirectOrRefreshPage(self.router.navMap.shop_cart_view);
                        // });
                        break;
                    case "shop:cart:standalone-clear":
                        // self.model.shoppingCartClear(function (rez) {
                        //     self.router.redirectOrRefreshPage(self.router.navMap.shop_cart_view);
                        //     // self.router.refreshPage();
                        // });
                        break;
                    case "shop:cart:view":
                        // self.router.redirectOrRefreshPage(self.router.navMap.shop_cart_view);
                        break;
                    case "shop:cart:checkout":
                        // self.router.redirectOrRefreshPage(self.router.navMap.shop_cart_checkout_view);
                        break;
                    case "shop:cart:checkout:preview":
                        // self.pageShopCartCheckoutPreview();
                        break;
                    case "shop:cart:checkout:save":
                        // var _orderInfo = {};
                        // // use form serialize
                        // self.model.shoppingCartSave(_orderInfo, function(){

                        // });

                        break;
                    case "shop:cart:checkout-prepare":
                        // $('.shop-component-checkout-wizard .wizard').wizard('next');
                        break;
                    case "shop:cart:checkout-delivery":
                        // $('.shop-component-checkout-wizard .wizard').wizard('next');
                        break;

                    case "shop:search":
                        // self.model.getShopLocation();
                        break;

                    default:
                        // self.action_cartEmbeddedHide();
                        break;
                }
            });

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
                // debugger;
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