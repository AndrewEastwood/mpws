define("plugin/shop/js/toolbox", [
    'default/js/lib/sandbox',
    'customer/js/site',
    'cmn_jquery',
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'default/js/lib/cache',
    'plugin/shop/js/view/toolbox/menu'
], function (Sandbox, Site, $, _, Backbone, Cache) {

    var Router = Backbone.Router.extend({
        routes: {
            "shop/products": "products",
            "shop/orders": "orders",
            "shop/sales": "sales",
            "shop/prices": "prices",
        },

        initialize: function () {
            var self = this;
            // ToolboxMenu.render();
            Sandbox.eventSubscribe('global:page:index', function () {
                // debugger;
                // Sandbox.eventNotify('global:breadcrumb:show');
                // Site.addMenuItemLeft('SHOP');
                // $('#userMenu').append();
                self.dashboard();
            });
        },

        dashboard: function () {
            // debugger;
            Sandbox.eventNotify('plugin:toolbox:page:show', {
                name: 'ShopDashboard',
                el: $('<div>').text('34 New Orders')
            });
        },

        products: function () {
            require(['plugin/shop/js/view/toolbox/listProducts'], function (ListProducts) {
                // using this wrapper to cleanup previous view and create new one
                Cache.withObject('ListProducts', function (cachedView) {
                    // debugger;
                    // remove previous view
                    if (cachedView && cachedView.remove)
                        cachedView.remove();

                    // create new view
                    var listProducts = new ListProducts();
                    // Site.placeholders.shop.productListOverview.html(listProducts.el);
                    listProducts.fetchAndRender();

                    Sandbox.eventNotify('plugin:toolbox:page:show', {
                        name: 'ShopListProducts',
                        el: listProducts.$el
                    });

                    Sandbox.eventNotify("plugin:shop:toolbox:menu:refresh");
                    // return view object to pass it into this function at next invocation
                    return listProducts;
                });
            });
        },

        orders: function () {

            require(['plugin/shop/js/view/toolbox/listOrders'], function (ListOrders) {
                // using this wrapper to cleanup previous view and create new one
                Cache.withObject('ListOrders', function (cachedView) {
                    // debugger;
                    // remove previous view
                    if (cachedView && cachedView.remove)
                        cachedView.remove();

                    // create new view
                    var listOrders = new ListOrders();

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