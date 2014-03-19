define("plugin/toolbox/js/toolbox", [
    'default/js/lib/sandbox',
    'customer/js/site',
    'cmn_jquery',
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'default/js/lib/cache',
    'plugin/shop/js/view/toolbox/menu'
], function (Sandbox, Site, $, _, Backbone, Cache) {

    Sandbox.eventSubscribe('route', function (hash) {
        debugger;
    });

    Sandbox.eventSubscribe('global:loader:complete', function (hash) {
        debugger;
    });

    var Router = Backbone.Router.extend({
        routes: {
            "signin": "signin",
            "signout": "signout",
        },

        initialize: function () {
            var self = this;

            Sandbox.eventSubscribe('toolbox:page:login', function () {
                self.login();
            });

            Sandbox.eventSubscribe('toolbox:page:index', function () {
                Sandbox.eventNotify('toolbox:breadcrumb:show');
            });

        },

        signin: function () {

        },

        signout: function () {

        },

    });

    return Router;
});