define([
    'require',
    'jquery',
    'underscore',
    'backbone',
    'cachejs',
    'auth',

    'plugins/shop/toolbox/js/view/dashboard',
    'plugins/shop/toolbox/js/view/editProduct',
    'plugins/shop/toolbox/js/view/managerContent',
    'plugins/shop/toolbox/js/view/editOrder',
    'plugins/shop/toolbox/js/view/managerOrders',
    'plugins/shop/toolbox/js/view/editCategory',
    'plugins/shop/toolbox/js/view/editOrigin',
    'plugins/shop/toolbox/js/view/managerFeeds',
    'plugins/shop/toolbox/js/view/managerPromoCodes',
    'plugins/shop/toolbox/js/view/editPromo',
    'plugins/shop/toolbox/js/view/settings',
    'plugins/shop/toolbox/js/view/menu',

    'plugins/shop/common/js/model/setting'
], function (require, $, _, Backbone, Cache, Auth,

    ViewDashboard,
    ViewEditProduct,
    ManagerContent,
    ViewEditOrder,
    ManagerOrders,
    ViewEditCategory,
    ViewEditOrigin,
    ManagerFeeds,
    ManagerPromoCodes,
    ViewEditPromo,
    Settings,
    ViewMenu,

    SiteSettings) {

    var View = Backbone.View.extend({

        settings: {},

        urls: {},

        initialize: function (options, callback) {
            this.pending = true;
            
            var that = this,
                settings = new SiteSettings();

            // configure plugin
            this.options = options || {};
            this.urls = options && options.urls || {};

            Backbone.on('changed:plugin-shop-currency', function (currencyName) {
                that.settings._user.activeCurrency = currencyName;
                settings.fetch().done(function () {
                    that.settings = settings.toSettings();
                });
            });

            // fetch data
            settings.fetch().done(function () {
                that.settings = settings.toSettings();
                that.settings._user = {
                    activeCurrency: that.settings.MISC.SiteDefaultPriceCurrencyType
                };
                callback();
            });
        },

        menu: function (options) {
            var menu = new ViewMenu(options);
            menu.render();
            return menu;
        },
        dashboard: function () {
            // create new view
            var dashboard = new ViewDashboard();
            dashboard.render();
            return dashboard;
        },
        productCreate: function () {
            if (!Auth.canDo('CanMaintain') && !Auth.canDo('shop_CREATE_PRODUCT')) {
                Backbone.history.navigate('!/', true);
                return;
            }
            var editProduct = new ViewEditProduct();
            editProduct.render();
            return editProduct;
        },
        productEdit: function (productID) {
            if (!Auth.canDo('CanMaintain') && !Auth.canDo('shop_EDIT_PRODUCT')) {
                Backbone.history.navigate('!/', true);
                return;
            }
            var editProduct = new ViewEditProduct();
            editProduct.model.set('ID', productID);
            editProduct.model.fetch();
            return editProduct;
        },
        contentList: function () {
            return this.contentListByStatus();
        },
        contentListByStatus: function (status) {
            if (!Auth.canDo('CanMaintain') && !Auth.canDo('shop_MENU_CONTENT')) {
                Backbone.history.navigate('!/', true);
                return;
            }
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
            return managerContent;
        },
        orderEdit: function (orderID) {
            if (!Auth.canDo('CanMaintain') && !Auth.canDo('shop_EDIT_ORDER')) {
                Backbone.history.navigate('!/', true);
                return;
            }
            var editOrder = new ViewEditOrder();
            editOrder.model.set('ID', orderID);
            editOrder.model.fetch();
            return editOrder;
        },
        ordersList: function () {
            return this.ordersListByStatus();
        },
        ordersListByStatus: function (status) {
            if (!Auth.canDo('CanMaintain') && !Auth.canDo('shop_MENU_ORDERS')) {
                Backbone.history.navigate('!/', true);
                return;
            }
            // set active menu
            var options = status ? {
                status: status
            } : {};
            var managerOrders = new ManagerOrders(options);
            managerOrders.viewOrdersList.collection.fetch({
                reset: true
            });
            return managerOrders;
        },

        categoryEdit: function (categoryID) {
            if (!Auth.canDo('CanMaintain') && !Auth.canDo('shop_EDIT_CATEGORY')) {
                Backbone.history.navigate('!/', true);
                return;
            }
            var editCategory = new ViewEditCategory();
            editCategory.model.set('ID', categoryID);
            editCategory.model.fetch();
            return editCategory;
        },
        categoryCreate: function () {
            if (!Auth.canDo('CanMaintain') && !Auth.canDo('shop_CREATE_CATEGORY')) {
                Backbone.history.navigate('!/', true);
                return;
            }
            var editCategory = new ViewEditCategory();
            editCategory.model.fetch();
            return editCategory;
        },
        originEdit: function (originID) {
            if (!Auth.canDo('CanMaintain') && !Auth.canDo('shop_EDIT_ORIGIN')) {
                Backbone.history.navigate('!/', true);
                return;
            }
            var editOrigin = new ViewEditOrigin();
            editOrigin.model.set('ID', originID);
            editOrigin.model.fetch();
            return editOrigin;
        },
        originCreate: function () {
            if (!Auth.canDo('CanMaintain') && !Auth.canDo('shop_CREATE_ORIGIN')) {
                Backbone.history.navigate('!/', true);
                return;
            }
            var editOrigin = new ViewEditOrigin();
            editOrigin.model.fetch();
            return editOrigin;
        },

        reports: function () {
        },

        feeds: function () {
            if (!Auth.canDo('CanMaintain') && !Auth.canDo('shop_MENU_FEEDS')) {
                Backbone.history.navigate('!/', true);
                return;
            }
            // set active menu
            var managerFeeds = new ManagerFeeds();
            managerFeeds.collection.fetch({
                reset: true
            });
            return managerFeeds;
        },

        promo: function () {
            if (!Auth.canDo('CanMaintain') && !Auth.canDo('shop_MENU_PROMO')) {
                Backbone.history.navigate('!/', true);
                return;
            }
            // set active menu
            var managerPromoCodes = new ManagerPromoCodes();
            managerPromoCodes.viewPromosList.collection.fetch({
                reset: true
            });
            return managerPromoCodes;
        },

        promoEdit: function (promoID) {
            if (!Auth.canDo('CanMaintain') && !Auth.canDo('shop_EDIT_PROMO')) {
                Backbone.history.navigate('!/', true);
                return;
            }
            // set active menu
            var viewEditPromo = new ViewEditPromo();
            viewEditPromo.model.set('ID', promoID);
            viewEditPromo.model.fetch();
            return viewEditPromo;
        },

        promoCreate: function () {
            if (!Auth.canDo('CanMaintain') && !Auth.canDo('shop_CREATE_PROMO')) {
                Backbone.history.navigate('!/', true);
                return;
            }
            // set active menu
            var viewEditPromo = new ViewEditPromo();
            viewEditPromo.render();
            return viewEditPromo;
        },

        shopSettings: function () {
            if (!Auth.canDo('CanMaintain') && !Auth.canDo('shop_MENU_SETTINGS')) {
                Backbone.history.navigate('!/', true);
                return;
            }
            var pluginSettings = new Settings();
            pluginSettings.viewDeliveriesList.collection.fetch({
                reset: true
            });
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
            pluginSettings.viewWidgetPrivatBankExchageRates.model.fetch();
            return pluginSettings;
        }

    });

    return View;
});