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

    var Router = Backbone.Router.extend({
        routes: {
            // "shop/stats": "stats",
            "shop/products": "products",
            "shop/orders": "ordersList",
            "shop/orders/:status": "ordersList",
            "shop/order/:id": "orderDetails",
            "shop/sales": "sales",
            "shop/prices": "prices",
            "shop/reports": "reports"
        },

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

        products: function () {
            Sandbox.eventNotify('global:menu:set-active', '.menu-shop-products');
            require(['plugin/shop/toolbox/js/view/productManager'], function (ProductManager) {
                // create new view
                var productManager = new ProductManager();
                productManager.render();

                Sandbox.eventNotify('global:content:render', {
                    name: 'CommonBodyCenter',
                    el: productManager.$el
                });

                // set page title
                Sandbox.eventNotify('global:content:render', {
                    name: 'CustomerPageName',
                    el: "Товари"
                });
            });
        },
        orderDetails: function (orderID) {
            require(['plugin/shop/toolbox/js/view/popupOrder'], function (PopupOrderEntry) {
                var popupOrder = new PopupOrderEntry();
                popupOrder.model.set('ID', orderID);
                popupOrder.model.fetch();
                popupOrder.$dialog.onHide(function () {
                    Backbone.history.history.back();
                });
            });
        },
        ordersList: function (status) {
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
                debugger;
                var managerOrders = new ManagerOrders({
                    status: status
                });
                managerOrders.collection.fetch({reset: true});

                Sandbox.eventNotify('global:content:render', {
                    name: 'CommonBodyCenter',
                    el: managerOrders.$el
                });
            });
        },

        sales: function () {
            
        },

        prices: function () {
            
        },

        reports: function () {
            Sandbox.eventNotify('global:menu:set-active', '.menu-shop-reports');
        },

    });

    return Router;
});