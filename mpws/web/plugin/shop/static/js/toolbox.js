define("plugin/shop/js/toolbox", [
    'default/js/lib/sandbox',
    'customer/js/site',
    'cmn_jquery',
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'plugin/shop/js/view/toolboxMenu'
], function (Sandbox, Site, $, _, Backbone, ToolboxMenu) {

    var Router = Backbone.Router.extend({
        routes: {
            "shop/manager": "manager",
            "shop/orders": "orders"
        },

        initialize: function () {
            ToolboxMenu.render();

            Sandbox.eventSubscribe('site:page:index', function () {
                // debugger;
                Site.showBreadcrumbLocation();
                Site.addMenuItemLeft('SHOP');
                $('#userMenu').append();
            });
        },

        manager: function () {

        },

        orders: function () {

        }

    });

    return Router;
});