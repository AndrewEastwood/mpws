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
                    // create new view
                var stats = new Stats();
                stats.refresh();

                Sandbox.eventNotify('global:content:render', {
                    name: 'CommonBodyCenter',
                    el: stats.$el,
                    append: true
                });
            });
        },

        products: function () {
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

        orders: function () {
            require(['plugin/shop/toolbox/js/view/listOrders'], function (ListOrders) {
                // create new view
                var listOrders = new ListOrders();
                listOrders.customDataSources.fetch({reset: true});

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

    });

    return Router;
});