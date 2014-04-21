define("plugin/account/toolbox/js/router", [
    'default/js/lib/sandbox',
    'cmn_jquery',
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'plugin/account/toolbox/js/view/menu'
], function (Sandbox, $, _, Backbone) {

    var Router = Backbone.Router.extend({
        routes: {
            "account/profiles": "profiles"
        },

        initialize: function () {
            var self = this;

            // Sandbox.eventSubscribe('global:page:login', function () {
            //     self.login();
            // });

            // Sandbox.eventSubscribe('global:page:index', function () {
            //     // debugger;
            //     Sandbox.eventNotify('global:breadcrumb:show');
            //     // Site.addMenuItemLeft('PROFILE');
            //     // $('#userMenu').append($('<li><a href="#"><i class="glyphicon glyphicon-comment"></i> Shoutbox <span class="badge badge-info">20</span></a></li>'));
            // });

        },

        profiles: function () {
            // Sandbox.eventNotify('site:content:render', {
            //     name: 'AccountLogin',
            //     el: "gdfgdfgdfgdfgdf"
            // });
        }

    });

    return Router;
});