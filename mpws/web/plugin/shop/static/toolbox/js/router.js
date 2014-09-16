define("plugin/shop/toolbox/js/router", [
    'require',
    'default/js/lib/sandbox',
    'cmn_jquery',
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'default/js/lib/cache'
], function (require, Sandbox, $, _, Backbone, Cache) {

    Sandbox.eventSubscribe('global:auth:status:active', function (data) {
        require(['plugin/shop/toolbox/js/view/menu'], function (ViewMenu) {
            var menu = new ViewMenu();
            menu.render();
            Sandbox.eventNotify('customer:menu:set', {
                name: 'MenuLeft',
                el: menu.$el,
                append: true
            });
        });
    });

    var routes = {
        // "shop/stats": "stats",
        "shop/content": "contentList",
        "shop/content/:status": "contentListPage",
        "shop/product/new": "productCreate",
        "shop/product/edit/:id": "productEdit",
        "shop/orders": "ordersList",
        "shop/orders/:status": "ordersListPage",
        "shop/order/edit/:id": "orderEdit",
        "shop/order/print/:id": "orderPrint",
        "shop/order/email_tracking/:id": "orderEmailTracking",
        "shop/order/email_reciept/:id": "orderEmailReceipt",
        "shop/origin/new": "originCreate",
        "shop/origin/edit/:id": "originEdit",
        "shop/category/new": "categoryCreate",
        "shop/category/edit/:id": "categoryEdit",
        "shop/reports": "reports"
    };

    var Router = Backbone.Router.extend({

        routes: routes,

        urls: _(routes).invert(),

        initialize: function () {
            var self = this;
            Sandbox.eventSubscribe('plugin:dashboard:ready', function () {
                self.dashboard();
            });
        },

        dashboard: function () {
            require(['plugin/shop/toolbox/js/view/dashboard'], function (ViewDashboard) {
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
            require(['plugin/shop/toolbox/js/view/popupProduct'], function (PopupProductEntry) {
                var popupProduct = new PopupProductEntry();
                popupProduct.model.fetch();
                popupProduct.$dialog.onHide(function () {
                    Backbone.history.history.back();
                });
            });
        },
        productEdit: function (productID) {
            require(['plugin/shop/toolbox/js/view/popupProduct'], function (PopupProductEntry) {
                var popupProduct = new PopupProductEntry();
                popupProduct.model.fetch({ data: { id: productID} });
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
                var options = status ? {status: status} : {};
                var managerContent = new ManagerContent(options);
                managerContent.viewProductsList.collection.fetch({reset: true});
                managerContent.viewCatergoriesTree.collection.fetch({reset: true});
                managerContent.viewOriginsList.collection.fetch({reset: true});

                Sandbox.eventNotify('global:content:render', {
                    name: 'CommonBodyCenter',
                    el: managerContent.$el
                });

                // set page title
                // Sandbox.eventNotify('global:content:render', {
                //     name: 'CustomerPageName',
                //     el: "Товари"
                // });
            });
        },
        orderPrint: function (orderID) {},
        orderEmailTracking: function (orderID) {},
        orderEmailReceipt: function (orderID) {},
        orderEdit: function (orderID) {
            require(['plugin/shop/toolbox/js/view/popupOrder'], function (PopupOrderEntry) {
                var popupOrder = new PopupOrderEntry();
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
            // // set page title
            // Sandbox.eventNotify('global:content:render', {
            //     name: 'CustomerPageName',
            //     el: "Замовлення"
            // });
            // activeTabPage = activeTabPage || 'new';
            require(['plugin/shop/toolbox/js/view/managerOrders'], function (ManagerOrders) {
                // create new view
                // debugger;
                var options = status ? {status: status} : {};

                var managerOrders = new ManagerOrders(options);
                managerOrders.viewOrdersList.collection.fetch({reset: true});

                Sandbox.eventNotify('global:content:render', {
                    name: 'CommonBodyCenter',
                    el: managerOrders.$el
                });
            });
        },

        categoryEdit: function (categoryID) {
            require(['plugin/shop/toolbox/js/view/popupCategory'], function (PopupCategoryEntry) {
                var popupCategory = new PopupCategoryEntry();
                popupCategory.model.fetch({ data: { id: categoryID} });
                popupCategory.$dialog.onHide(function () {
                    Backbone.history.history.back();
                });
            });
        },
        categoryCreate: function () {
            require(['plugin/shop/toolbox/js/view/popupCategory'], function (PopupCategoryEntry) {
                var popupCategory = new PopupCategoryEntry();
                popupCategory.model.fetch();
                popupCategory.$dialog.onHide(function () {
                    Backbone.history.history.back();
                });
            });
        },
        originEdit: function (originID) {
            require(['plugin/shop/toolbox/js/view/popupOrigin'], function (PopupOriginEntry) {
                var popupOrigin = new PopupOriginEntry();
                popupOrigin.model.set('ID', originID);
                popupOrigin.model.fetch();
                popupOrigin.$dialog.onHide(function () {
                    Backbone.history.history.back();
                });
            });
        },
        originCreate: function () {
            require(['plugin/shop/toolbox/js/view/popupOrigin'], function (PopupOriginEntry) {
                var popupOrigin = new PopupOriginEntry();
                popupOrigin.model.fetch();
                popupOrigin.$dialog.onHide(function () {
                    Backbone.history.history.back();
                });
            });
        },

        reports: function () {
            Sandbox.eventNotify('global:menu:set-active', '.menu-shop-reports');
        }

    });

    return Router;
});