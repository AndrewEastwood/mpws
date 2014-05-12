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
            "shop/dashboard": "dashboard",
            "shop/products": "products",
            "shop/orders": "orders",
            "shop/sales": "sales",
            "shop/prices": "prices",
        },

        initialize: function () {
            var self = this;
            Sandbox.eventSubscribe('global:page:index', function () {
                self.dashboard();
            });
        },

        dashboard: function () {
            require(['plugin/shop/toolbox/js/view/dashboard'], function (Dashboard) {
                // using this wrapper to cleanup previous view and create new one
                Cache.withObject('Dashboard', function (cachedView) {
                    // debugger;
                    // remove previous view
                    // if (cachedView && cachedView.remove)
                    //     cachedView.remove();

                    // create new view
                    var dashboard = cachedView || new Dashboard();

                    dashboard.fetchAndRender();

                    Sandbox.eventNotify('plugin:toolbox:page:show', {
                        name: 'ShopDashboard',
                        el: dashboard.$el
                    });

                    // Sandbox.eventNotify("plugin:shop:toolbox:menu:refresh");
                    // return view object to pass it into this function at next invocation
                    return dashboard;
                });
            });
        },

        products: function () {

            require(['plugin/shop/toolbox/js/view/productManager'], function (ProductManager) {
                // using this wrapper to cleanup previous view and create new one
                Cache.withObject('ProductManager', function (cachedView) {
                    // debugger;
                    // remove previous view
                    // if (cachedView && cachedView.remove)
                    //     cachedView.remove();

                    // create new view
                    var productManager = cachedView || new ProductManager();

                    productManager.fetchAndRender();

                    Sandbox.eventNotify('plugin:toolbox:page:show', {
                        name: 'ShopListProducts',
                        el: productManager.$el
                    });

                    // Sandbox.eventNotify("plugin:shop:toolbox:menu:refresh");
                    // return view object to pass it into this function at next invocation
                    return productManager;
                });
            });
        },

        orders: function () {

            require(['plugin/shop/toolbox/js/view/listOrders'], function (ListOrders) {
                // using this wrapper to cleanup previous view and create new one
                Cache.withObject('ListOrders', function (cachedView) {
                    // debugger;
                    // remove previous view
                    // if (cachedView && cachedView.remove)
                    //     cachedView.remove();

                    // create new view
                    var listOrders = cachedView || new ListOrders();

                    listOrders.fetchAndRender();

                    Sandbox.eventNotify('plugin:toolbox:page:show', {
                        name: 'ShopListOrders',
                        el: listOrders.$el
                    });

                    // return view object to pass it into this function at next invocation
                    return listOrders;
                });
            });
        },

        sales: function () {
            
        },

        prices: function () {
            
        },

    });

    return Router;
});