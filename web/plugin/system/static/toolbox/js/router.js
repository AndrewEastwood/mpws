define("plugin/system/toolbox/js/router", [
    'require',
    'default/js/lib/sandbox',
    'cmn_jquery',
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'default/js/lib/cache',
    'plugin/system/common/js/collection/settings',
    'default/js/lib/handlebarsHelpers'
], function (require, Sandbox, $, _, Backbone, Cache, SiteSettings) {

    var settings = new SiteSettings();
    var $dfdSettings = settings.fetch();

    Sandbox.eventSubscribe('global:auth:status:active', function (data) {
        require(['plugin/system/toolbox/js/view/menu'], function (ViewMenu) {
            var menu = new ViewMenu();
            menu.render();
            Sandbox.eventNotify('customer:menu:set', {
                name: 'MenuLeft',
                el: menu.$el,
                append: true
            });
        });
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
        "!/system": "dashboard",
        "!/system/patches": "patches",
        "!/system/customers": "customers",
        "!/system/customers/new": "customersCreate",
        "!/system/customers/edit/:id": "customersEdit",
        "!/system/settings": "settings",
        "!/system/plugins": "settings",
        "!/system/themes": "themes"
    };

    var Router = Backbone.Router.extend({

        settings: null,

        routes: routes,

        urls: _(routes).invert(),

        initialize: function () {
            var self = this;
            if (APP.dfd.dashboard) {
                APP.dfd.dashboard.done(function () {
                    self.dashboard();
                });
            }
        },

        dashboard: function () {
            require(['plugin/system/toolbox/js/view/dashboard'], function (ViewDashboard) {
                // debugger;
                // create new view
                var dashboard = new ViewDashboard();
                dashboard.render();
                Sandbox.eventNotify('global:content:render', {
                    name: 'DashboardForPlugin_shop',
                    el: dashboard.$el
                });
            });
        },
        productCreate: function () {
            require(['plugin/shop/toolbox/js/view/popupProduct'], function (ViewPopupProduct) {
                var popupProduct = new ViewPopupProduct();
                popupProduct.render();
                popupProduct.$dialog.onHide(function () {
                    Backbone.history.history.back();
                });
            });
        },
        productEdit: function (productID) {
            require(['plugin/shop/toolbox/js/view/popupProduct'], function (ViewPopupProduct) {
                var popupProduct = new ViewPopupProduct();
                popupProduct.model.fetch({
                    data: {
                        id: productID
                    }
                });
                popupProduct.$dialog.onHide(function () {
                    Backbone.history.history.back();
                });
            });
        },
        contentList: function () {
            this.contentListPage();
        },
        contentListPage: function (status) {
            Sandbox.eventNotify('global:menu:set-active', '.menu-shop-products');
            require(['plugin/shop/toolbox/js/view/managerContent'], function (ManagerContent) {
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

                Sandbox.eventNotify('global:content:render', {
                    name: 'CommonBodyCenter',
                    el: managerContent.$el
                });
            });
        },
        orderPrint: function (orderID) {},
        orderEmailTracking: function (orderID) {},
        orderEmailReceipt: function (orderID) {},
        orderEdit: function (orderID) {
            require(['plugin/shop/toolbox/js/view/popupOrder'], function (ViewPopupOrder) {
                var popupOrder = new ViewPopupOrder();
                popupOrder.model.set('ID', orderID);
                popupOrder.model.fetch();
                popupOrder.$dialog.onHide(function () {
                    Backbone.history.history.back();
                });
            });
        },
        ordersList: function () {
            this.ordersListPage();
        },
        ordersListPage: function (status) {
            // set active menu
            Sandbox.eventNotify('global:menu:set-active', '.menu-shop-orders');
            require(['plugin/shop/toolbox/js/view/managerOrders'], function (ManagerOrders) {
                var options = status ? {
                    status: status
                } : {};
                var managerOrders = new ManagerOrders(options);
                managerOrders.viewOrdersList.collection.fetch({
                    reset: true
                });

                Sandbox.eventNotify('global:content:render', {
                    name: 'CommonBodyCenter',
                    el: managerOrders.$el
                });
            });
        },

        categoryEdit: function (categoryID) {
            require(['plugin/shop/toolbox/js/view/popupCategory'], function (ViewPopupCategory) {
                var popupCategory = new ViewPopupCategory();
                popupCategory.model.fetch({
                    data: {
                        id: categoryID
                    }
                });
                popupCategory.$dialog.onHide(function () {
                    Backbone.history.history.back();
                });
            });
        },
        categoryCreate: function () {
            require(['plugin/shop/toolbox/js/view/popupCategory'], function (ViewPopupCategory) {
                var popupCategory = new ViewPopupCategory();
                popupCategory.model.fetch();
                popupCategory.$dialog.onHide(function () {
                    Backbone.history.history.back();
                });
            });
        },
        originEdit: function (originID) {
            require(['plugin/shop/toolbox/js/view/popupOrigin'], function (ViewPopupOrigin) {
                var popupOrigin = new ViewPopupOrigin();
                popupOrigin.model.set('ID', originID);
                popupOrigin.model.fetch();
                popupOrigin.$dialog.onHide(function () {
                    Backbone.history.history.back();
                });
            });
        },
        originCreate: function () {
            require(['plugin/shop/toolbox/js/view/popupOrigin'], function (ViewPopupOrigin) {
                var popupOrigin = new ViewPopupOrigin();
                popupOrigin.model.fetch();
                popupOrigin.$dialog.onHide(function () {
                    Backbone.history.history.back();
                });
            });
        },

        reports: function () {
            Sandbox.eventNotify('global:menu:set-active', '.menu-shop-reports');
        },

        feeds: function () {
            // set active menu
            Sandbox.eventNotify('global:menu:set-active', '.menu-shop-feeds');
            require(['plugin/shop/toolbox/js/view/managerFeeds'], function (ManagerFeeds) {
                var managerFeeds = new ManagerFeeds();
                managerFeeds.collection.fetch({
                    reset: true
                });
                Sandbox.eventNotify('global:content:render', {
                    name: 'CommonBodyCenter',
                    el: managerFeeds.$el
                });
            });
        },

        promo: function () {
            // set active menu
            Sandbox.eventNotify('global:menu:set-active', '.menu-shop-promo');
            require(['plugin/shop/toolbox/js/view/managerPromoCodes'], function (ManagerPromoCodes) {
                var managerPromoCodes = new ManagerPromoCodes();
                managerPromoCodes.viewPromosList.collection.fetch({
                    reset: true
                });
                Sandbox.eventNotify('global:content:render', {
                    name: 'CommonBodyCenter',
                    el: managerPromoCodes.$el
                });
            });
        },

        promoEdit: function (promoID) {
            // set active menu
            Sandbox.eventNotify('global:menu:set-active', '.menu-shop-promo');
            require(['plugin/shop/toolbox/js/view/popupPromo'], function (ViewPopupPromo) {
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
            Sandbox.eventNotify('global:menu:set-active', '.menu-shop-promo');
            require(['plugin/shop/toolbox/js/view/popupPromo'], function (ViewPopupPromo) {
                var viewPopupPromo = new ViewPopupPromo();
                viewPopupPromo.render();
                viewPopupPromo.$dialog.onHide(function () {
                    Backbone.history.history.back();
                });
            });
        },

        shopSettings: function () {
            Sandbox.eventNotify('global:menu:set-active', '.menu-shop-settings');
            require(['plugin/shop/toolbox/js/view/settings'], function (Settings) {
                var pluginSettings = new Settings();
                // delivery agencies
                // 
                pluginSettings.viewDeliveriesList.collection.fetch({
                    reset: true
                });
                pluginSettings.viewDeliveriesList.modelSelfService.fetch();
                pluginSettings.viewAlerts.collection.fetch({
                    reset: true
                });
                pluginSettings.viewWebsiteFormOrder.collection.fetch({
                    reset: true
                });
                pluginSettings.viewAddress.collection.fetch({
                    reset: true
                });
                pluginSettings.viewProduct.collection.fetch({
                    reset: true
                });
                pluginSettings.viewSEO.collection.fetch({
                    reset: true
                });
                pluginSettings.viewExchangeRates.collection.fetch({
                    reset: true
                });
                pluginSettings.viewExchangeRatesDisplay.modelDBPriceCurrency.fetch();
                pluginSettings.viewExchangeRatesDisplay.modelSiteDefaultCurrency.fetch();
                pluginSettings.viewExchangeRatesDisplay.modelShowSiteCurrencySwitcher.fetch();
                pluginSettings.viewExchangeRatesDisplay.collection.fetch({
                    reset: true
                });
                pluginSettings.viewWidgetPrivatBankExchageRates.model.fetch();
                Sandbox.eventNotify('global:content:render', {
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