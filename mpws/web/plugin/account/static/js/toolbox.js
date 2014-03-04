define("plugin/account/js/toolbox", [
    'default/js/lib/sandbox',
    'customer/js/site',
    'cmn_jquery',
    'default/js/lib/underscore',
    'default/js/lib/backbone',
], function (Sandbox, Site, $, _, Backbone) {

    var Router = Backbone.Router.extend({
        routes: {
            "account/profiles": "profiles"
        },

        initialize: function () {
            Sandbox.eventSubscribe('site:page:404', function () {
                $('.MPWSPageBody').html('404');
            });

            Sandbox.eventSubscribe('site:page:index', function () {
                // debugger;
                Site.showBreadcrumbLocation();
                Site.addMenuItemLeft('PROFILE');
                $('#userMenu').append($('<li><a href="#"><i class="glyphicon glyphicon-comment"></i> Shoutbox <span class="badge badge-info">20</span></a></li>'));
            });

        },

        profiles: function () {

        }

    });

    return Router;
});