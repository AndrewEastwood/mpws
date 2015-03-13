define([
    'require',
    'jquery',
    'underscore',
    'backbone',
    'cachejs',
    'auth',
    'plugins/shop/common/js/model/setting'
], function (require, $, _, Backbone, Cache, Auth, SiteSettings) {

    var menuView = null;
    var settings = new SiteSettings();
    var $dfdSettings = settings.fetch();

    Auth.on('registered', function () {
        require(['plugins/shop/toolbox/js/view/menu'], function (ViewMenu) {
            menuView = new ViewMenu();
            menuView.render();
            APP.Sandbox.eventNotify('global:content:render', {
                name: 'MenuForPlugin_shop',
                el: menuView.$el
            });
        });
    });

    Auth.on('guest', function () {
        if (menuView) {
            menuView.remove();
        }
    });

    Backbone.on('changed:plugin-shop-currency', function () {
        settings.fetch({
            success: function() {
                Router.prototype.settings = settings.toSettings();
            }
        });
    });

    var routes = {
        // "shop/stats": "stats",
        "!/shop/content": "contentList",
        "!/shop/content/:status": "contentListByStatus",
        "!/shop/product/new": "productCreate",
        "!/shop/product/edit/:id": "productEdit",
        "!/shop/orders": "ordersList",
        "!/shop/orders/:status": "ordersListByStatus",
        "!/shop/order/edit/:id": "orderEdit",
        "!/shop/order/print/:id": "orderPrint",
        "!/shop/order/email_tracking/:id": "orderEmailTracking",
        "!/shop/order/email_reciept/:id": "orderEmailReceipt",
        "!/shop/origin/new": "originCreate",
        "!/shop/origin/edit/:id": "originEdit",
        "!/shop/category/new": "categoryCreate",
        "!/shop/category/edit/:id": "categoryEdit",
        "!/shop/reports": "reports",
        "!/shop/settings": "shopSettings",
        "!/shop/promo": "promo",
        "!/shop/promo/edit/:id": "promoEdit",
        "!/shop/promo/new": "promoCreate",
        "!/shop/feeds": "feeds"
    };

    var Router = Backbone.Router.extend({

        settings: null,

        routes: routes,

        urls: _(routes).invert(),

        initialize: function () {
            var self = this;
            // if (APP.dfd.dashboard) {
            //     APP.dfd.dashboard.done(function () {
            //         self.dashboard();
            //     });
            // }
            APP.Sandbox.eventSubscribe('global:page:index', function () {
                self.dashboard();
            });
        },

        dashboard: function () {
            require(['plugins/shop/toolbox/js/view/dashboard'], function (ViewDashboard) {
                // debugger;
                // create new view
                var dashboard = new ViewDashboard();
                dashboard.render();
                APP.Sandbox.eventNotify('global:content:render', {
                    name: 'DashboardForPlugin_shop',
                    el: dashboard.$el
                });
            });
        },
        productCreate: function () {
            require(['plugins/shop/toolbox/js/view/editProduct'], function (ViewEditProduct) {
                var editProduct = new ViewEditProduct();
                editProduct.render();
                APP.Sandbox.eventNotify('global:content:render', {
                    name: 'CommonBodyCenter',
                    el: editProduct.$el
                });
                // editProduct.$dialog.onHide(function () {
                //     Backbone.history.history.back();
                // });
            });
        },
        productEdit: function (productID) {
            require(['plugins/shop/toolbox/js/view/editProduct'], function (ViewEditProduct) {
                var editProduct = new ViewEditProduct();
                editProduct.model.set('ID', productID);
                editProduct.model.fetch();
                // editProduct.model.fetch({
                //     data: {
                //         id: productID
                //     }
                // });
                APP.Sandbox.eventNotify('global:content:render', {
                    name: 'CommonBodyCenter',
                    el: editProduct.$el
                });
                // editProduct.$dialog.onHide(function () {
                //     Backbone.history.history.back();
                // });
            });
        },
        contentList: function () {
            this.contentListByStatus();
        },
        contentListByStatus: function (status) {
            APP.Sandbox.eventNotify('global:menu:set-active', '.menu-shop-products');
            require(['plugins/shop/toolbox/js/view/managerContent'], function (ManagerContent) {
                // create new view
                var options = status ? {
                    status: status
                } : {};
                var managerContent = new ManagerContent(options);
                managerContent.viewProductsList.collection.fetch({
                    reset: true
                });
                managerContent.viewCatergoriesTree.collection.fetch({
                    reset: true
                });
                managerContent.viewOriginsList.collection.fetch({
                    reset: true
                });

                APP.Sandbox.eventNotify('global:content:render', {
                    name: 'CommonBodyCenter',
                    el: managerContent.$el
                });
            });
        },
        orderPrint: function (orderID) {},
        orderEmailTracking: function (orderID) {},
        orderEmailReceipt: function (orderID) {},
        orderEdit: function (orderID) {
            require(['plugins/shop/toolbox/js/view/editOrder'], function (ViewEditOrder) {
                var editOrder = new ViewEditOrder();
                editOrder.model.set('ID', orderID);
                editOrder.model.fetch();

                APP.Sandbox.eventNotify('global:content:render', {
                    name: 'CommonBodyCenter',
                    el: editOrder.$el
                });
                // editOrder.$dialog.onHide(function () {
                //     Backbone.history.history.back();
                // });
            });
        },
        ordersList: function () {
            this.ordersListByStatus();
        },
        ordersListByStatus: function (status) {
            // set active menu
            APP.Sandbox.eventNotify('global:menu:set-active', '.menu-shop-orders');
            require(['plugins/shop/toolbox/js/view/managerOrders'], function (ManagerOrders) {
                var options = status ? {
                    status: status
                } : {};
                var managerOrders = new ManagerOrders(options);
                managerOrders.viewOrdersList.collection.fetch({
                    reset: true
                });

                APP.Sandbox.eventNotify('global:content:render', {
                    name: 'CommonBodyCenter',
                    el: managerOrders.$el
                });
            });
        },

        categoryEdit: function (categoryID) {
            require(['plugins/shop/toolbox/js/view/editCategory'], function (ViewEditCategory) {
                var editCategory = new ViewEditCategory();
                editCategory.model.set('ID', categoryID);
                editCategory.model.fetch();
                APP.Sandbox.eventNotify('global:content:render', {
                    name: 'CommonBodyCenter',
                    el: editCategory.$el
                });
                // editCategory.$dialog.onHide(function () {
                //     Backbone.history.history.back();
                // });
            });
        },
        categoryCreate: function () {
            require(['plugins/shop/toolbox/js/view/editCategory'], function (ViewEditCategory) {
                var editCategory = new ViewEditCategory();
                editCategory.model.fetch();
                APP.Sandbox.eventNotify('global:content:render', {
                    name: 'CommonBodyCenter',
                    el: editCategory.$el
                });
                // editCategory.$dialog.onHide(function () {
                //     Backbone.history.history.back();
                // });
            });
        },
        originEdit: function (originID) {
            require(['plugins/shop/toolbox/js/view/editOrigin'], function (ViewEditOrigin) {
                var editOrigin = new ViewEditOrigin();
                editOrigin.model.set('ID', originID);
                editOrigin.model.fetch();
                APP.Sandbox.eventNotify('global:content:render', {
                    name: 'CommonBodyCenter',
                    el: editOrigin.$el
                });
                // editOrigin.$dialog.onHide(function () {
                //     Backbone.history.history.back();
                // });
            });
        },
        originCreate: function () {
            require(['plugins/shop/toolbox/js/view/editOrigin'], function (ViewEditOrigin) {
                var editOrigin = new ViewEditOrigin();
                editOrigin.model.fetch();
                APP.Sandbox.eventNotify('global:content:render', {
                    name: 'CommonBodyCenter',
                    el: editOrigin.$el
                });
                // editOrigin.$dialog.onHide(function () {
                //     Backbone.history.history.back();
                // });
            });
        },

        reports: function () {
            APP.Sandbox.eventNotify('global:menu:set-active', '.menu-shop-reports');
        },

        feeds: function () {
            // set active menu
            APP.Sandbox.eventNotify('global:menu:set-active', '.menu-shop-feeds');
            require(['plugins/shop/toolbox/js/view/managerFeeds'], function (ManagerFeeds) {
                var managerFeeds = new ManagerFeeds();
                managerFeeds.collection.fetch({
                    reset: true
                });
                APP.Sandbox.eventNotify('global:content:render', {
                    name: 'CommonBodyCenter',
                    el: managerFeeds.$el
                });
            });
        },

        promo: function () {
            // set active menu
            APP.Sandbox.eventNotify('global:menu:set-active', '.menu-shop-promo');
            require(['plugins/shop/toolbox/js/view/managerPromoCodes'], function (ManagerPromoCodes) {
                var managerPromoCodes = new ManagerPromoCodes();
                managerPromoCodes.viewPromosList.collection.fetch({
                    reset: true
                });
                APP.Sandbox.eventNotify('global:content:render', {
                    name: 'CommonBodyCenter',
                    el: managerPromoCodes.$el
                });
            });
        },

        promoEdit: function (promoID) {
            // set active menu
            APP.Sandbox.eventNotify('global:menu:set-active', '.menu-shop-promo');
            require(['plugins/shop/toolbox/js/view/popupPromo'], function (ViewPopupPromo) {
                var viewPopupPromo = new ViewPopupPromo();
                viewPopupPromo.model.set('ID', promoID);
                viewPopupPromo.model.fetch();
                viewPopupPromo.$dialog.onHide(function () {
                    Backbone.history.history.back();
                });
            });
        },

        promoCreate: function () {
            // set active menu
            APP.Sandbox.eventNotify('global:menu:set-active', '.menu-shop-promo');
            require(['plugins/shop/toolbox/js/view/popupPromo'], function (ViewPopupPromo) {
                var viewPopupPromo = new ViewPopupPromo();
                viewPopupPromo.render();
                viewPopupPromo.$dialog.onHide(function () {
                    Backbone.history.history.back();
                });
            });
        },

        shopSettings: function () {
            APP.Sandbox.eventNotify('global:menu:set-active', '.menu-shop-settings');
            require(['plugins/shop/toolbox/js/view/settings'], function (Settings) {
                var pluginSettings = new Settings();
                // delivery agencies
                // 
                pluginSettings.viewDeliveriesList.collection.fetch({
                    reset: true
                });
                // pluginSettings.viewDeliveriesList.modelSelfService.fetch();
                pluginSettings.viewAlerts.model.fetch({
                    reset: true
                });
                pluginSettings.viewWebsiteFormOrder.model.fetch({
                    reset: true
                });
                pluginSettings.viewAddress.collection.fetch({
                    reset: true
                });
                pluginSettings.viewProduct.model.fetch({
                    reset: true
                });
                pluginSettings.viewSEO.model.fetch({
                    reset: true
                });
                pluginSettings.viewExchangeRates.collection.fetch({
                    reset: true
                });
                // pluginSettings.viewExchangeRatesDisplay.modelDBPriceCurrency.fetch();
                // pluginSettings.viewExchangeRatesDisplay.modelSiteDefaultCurrency.fetch();
                // pluginSettings.viewExchangeRatesDisplay.modelShowSiteCurrencySwitcher.fetch();
                // pluginSettings.viewExchangeRatesDisplay.collection.fetch({
                //     reset: true
                // });
                pluginSettings.viewWidgetPrivatBankExchageRates.model.fetch();
                APP.Sandbox.eventNotify('global:content:render', {
                    name: 'CommonBodyCenter',
                    el: pluginSettings.$el
                });
            });
        }

    }, {
        preload: function (callback) {
            $dfdSettings.done(function () {
                Router.prototype.settings = settings.toSettings();
                // console.log('shop settings ready: calling callback');
                callback();
            });
        }
    });

    return Router;
});