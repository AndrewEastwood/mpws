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
            "shop/stats": "stats",
            "shop/product_manager": "product_manager",
            "shop/orders": "orders",
            "shop/sales": "sales",
            "shop/prices": "prices",
        },

        initialize: function () {
            var self = this;
            Sandbox.eventSubscribe('global:page:index', function () {
                self.stats();
            });
        },

        stats: function () {
            require(['plugin/shop/toolbox/js/view/stats'], function (Stats) {
                // using this wrapper to cleanup previous view and create new one
                Cache.withObject('Stats', function (cachedView) {
                    // debugger;
                    // remove previous view
                    // if (cachedView && cachedView.remove)
                    //     cachedView.remove();

                    // create new view
                    var stats = cachedView || new Stats();

                    stats.show();

                    Sandbox.eventNotify('customer:toolbox:page:show', {
                        name: 'CommonBodyCenter',
                        el: stats.$el
                    });

                    // Sandbox.eventNotify("plugin:shop:toolbox:menu:refresh");
                    // return view object to pass it into this function at next invocation
                    return stats;
                });
            });
        },

        product_manager: function () {
            require(['plugin/shop/toolbox/js/view/productManager'], function (ProductManager) {
                // using this wrapper to cleanup previous view and create new one
                Cache.withObject('ProductManager', function (cachedView) {
                    // debugger;
                    // remove previous view
                    // if (cachedView && cachedView.remove)
                    //     cachedView.remove();

                    // create new view
                    var productManager = cachedView || new ProductManager();

                    productManager.render();

                    Sandbox.eventNotify('plugin:toolbox:page:show', {
                        name: 'CommonBodyCenter',
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