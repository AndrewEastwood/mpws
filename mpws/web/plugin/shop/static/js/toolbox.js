define("plugin/shop/js/toolbox", [
    'default/js/lib/sandbox',
    'customer/js/site',
    'cmn_jquery',
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'plugin/shop/js/view/toolboxMenu'
], function (Sandbox, Site, $, _, Backbone) {

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

        },

        sales: function () {

        },

        prices: function () {

        }

    });

    return Router;
});