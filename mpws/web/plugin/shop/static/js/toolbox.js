define("plugin/shop/js/toolbox", [
    'default/js/lib/sandbox',
    'customer/js/site',
    'cmn_jquery',
    'default/js/lib/underscore',
    'default/js/lib/backbone',
], function (Sandbox, Site, $, _, Backbone) {

    var Router = Backbone.Router.extend({
        routes: {
            "shop/manager": "manager",
            "shop/orders": "orders"
        },

        initialize: function () {
            Sandbox.eventSubscribe('site:page:index', function () {
                // debugger;
                Site.showBreadcrumbLocation();
                Site.addMenuItemLeft('SHOP');
                $('#userMenu').append($('<li><a href="#"><i class="glyphicon glyphicon-envelope"></i> Messages <span class="badge badge-info">20</span></a></li>'));
            });
        },

        manager: function () {

        },

        orders: function () {

        }

    });

    return Router;
});