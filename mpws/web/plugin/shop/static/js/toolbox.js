define("plugin/shop/js/toolbox", [
    'default/js/lib/sandbox',
    'customer/js/site',
    'cmn_jquery',
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'default/js/lib/cache',
    'plugin/shop/js/view/toolboxMenu'
], function (Sandbox, Site, $, _, Backbone, Cache) {

    var Router = Backbone.Router.extend({
        routes: {
            "shop/products": "products",
            "shop/orders": "orders",
            "shop/sales": "sales",
            "shop/prices": "prices",
        },

        initialize: function () {
            // ToolboxMenu.render();

            Sandbox.eventSubscribe('site:page:index', function () {
                // debugger;
                Sandbox.eventNotify('site:breadcrumb:show');
                // Site.addMenuItemLeft('SHOP');
                // $('#userMenu').append();
            });
        },

        products: function () {
            
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
                    // Site.placeholders.shop.productListOverview.html(listOrders.el);
                    listOrders.fetchAndRender();

                    Sandbox.eventNotify('site:content:render', {
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
            
        }

    });

    return Router;
});