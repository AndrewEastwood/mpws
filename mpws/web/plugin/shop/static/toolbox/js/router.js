define("plugin/shop/toolbox/js/router", [
    'default/js/lib/sandbox',
    'cmn_jquery',
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'default/js/lib/cache',
    'plugin/shop/toolbox/js/view/menu'
], function (Sandbox, $, _, Backbone, Cache) {

    var Router = Backbone.Router.extend({
        routes: {
            // "shop/stats": "stats",
            "shop/products": "products",
            "shop/orders": "ordersList",
            "shop/orders/:page": "ordersList",
            "shop/order/:id": "orderDetails",
            "shop/sales": "sales",
            "shop/prices": "prices",
            "shop/reports": "reports"
        },

        initialize: function () {
            var self = this;
            Sandbox.eventSubscribe('plugin:dashboard:ready', function () {
                self.stats();
            });
        },

        stats: function () {
            require(['plugin/shop/toolbox/js/view/stats'], function (Stats) {
                // debugger;
                // create new view
                var stats = new Stats();
                stats.model.fetch();

                Sandbox.eventNotify('global:content:render', {
                    name: 'DashboardForPlugin_shop',
                    el: stats.$el
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
            this.orders(false, orderID);
        },
        ordersList: function (activeTabPage) {
            this.orders(activeTabPage);
        },
        orders: function (activeTabPage, orderID) {
            Sandbox.eventNotify('global:menu:set-active', '.menu-shop-orders');
            require(['plugin/shop/toolbox/js/view/listOrders'], function (ListOrders) {
                // create new view
                var listOrders = new ListOrders();
                listOrders.customDataSources.fetch({reset: true});

                // debugger
                if (orderID)
                    listOrders.showOrder(orderID);
                if (activeTabPage)
                    listOrders.showPage(activeTabPage);

                Sandbox.eventNotify('global:content:render', {
                    name: 'CommonBodyCenter',
                    el: listOrders.$el
                });

                // set page title
                Sandbox.eventNotify('global:content:render', {
                    name: 'CustomerPageName',
                    el: "Замовлення"
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