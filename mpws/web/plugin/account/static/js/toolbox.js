define("plugin/account/js/toolbox", [
    'default/js/lib/sandbox',
    'customer/js/site',
    'cmn_jquery',
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'plugin/account/js/view/toolbox/menu'
], function (Sandbox, Site, $, _, Backbone) {

    var Router = Backbone.Router.extend({
        routes: {
            "account/profiles": "profiles"
        },

        initialize: function () {
            var self = this;

            Sandbox.eventSubscribe('toolbox:page:login', function () {
                self.login();
            });

            Sandbox.eventSubscribe('toolbox:page:index', function () {
                debugger;
                Sandbox.eventNotify('site:breadcrumb:show');
                // Site.addMenuItemLeft('PROFILE');
                // $('#userMenu').append($('<li><a href="#"><i class="glyphicon glyphicon-comment"></i> Shoutbox <span class="badge badge-info">20</span></a></li>'));
            });

        },

        profiles: function () {
            // Sandbox.eventNotify('site:content:render', {
            //     name: 'AccountLogin',
            //     el: "gdfgdfgdfgdfgdf"
            // });
        },

        login: function () {
            $('.MPWSPageBody').html('404');
        }

    });

    return Router;
});