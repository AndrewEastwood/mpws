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
        "shop/products": "productsList",
        "shop/products/:status": "productsListPage",
        "shop/product/edit/:id": "productEdit",
        "shop/orders": "ordersList",
        "shop/orders/:status": "ordersListPage",
        "shop/order/edit/:id": "orderEdit",
        "shop/order/print/:id": "orderPrint",
        "shop/order/email_tracking/:id": "orderEmailTracking",
        "shop/order/email_reciept/:id": "orderEmailReceipt",
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
        productEdit: function (productID) {

        },
        productsList: function () {
            this.productsListPage();
        },
        productsListPage: function (status) {
            Sandbox.eventNotify('global:menu:set-active', '.menu-shop-products');
            require(['plugin/shop/toolbox/js/view/managerProducts'], function (ManagerProducts) {
                // create new view
                var options = status ? {status: status} : {};
                var managerProducts = new ManagerProducts(options);
                managerProducts.collection.fetch({reset: true});

                Sandbox.eventNotify('global:content:render', {
                    name: 'CommonBodyCenter',
                    el: managerProducts.$el
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
                managerOrders.collection.fetch({reset: true});

                Sandbox.eventNotify('global:content:render', {
                    name: 'CommonBodyCenter',
                    el: managerOrders.$el
                });
            });
        },

        reports: function () {
            Sandbox.eventNotify('global:menu:set-active', '.menu-shop-reports');
        },

    });

    return Router;
});