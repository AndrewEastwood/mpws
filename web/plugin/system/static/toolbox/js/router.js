define("plugin/system/toolbox/js/router", [
    'default/js/lib/sandbox',
    'cmn_jquery',
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'plugin/system/common/js/model/user',
    'default/js/lib/auth',
    'default/js/lib/cache',
], function (Sandbox, $, _, Backbone, User, Auth, Cache) {

    // this is the user instance
    var menuView = null;
    var user = new User();

    Sandbox.eventSubscribe('global:auth:status:active', function (data) {
        var authUserID = Auth.getUserID();
        if (authUserID) {
            user.set('ID', authUserID);
            user.fetch();
            require(['plugin/system/toolbox/js/view/buttonUser'], function (ViewButtonUser) {
                var buttonUser = new ViewButtonUser({
                    model: user
                });
                buttonUser.render();
                Sandbox.eventNotify('global:content:render', {
                    name: 'TopMenuRight',
                    el: buttonUser.$el
                });
            });
        }
        // show this menu when user is can manage
        require(['plugin/system/toolbox/js/view/menu'], function (ViewMenu) {
            menuView = new ViewMenu();
            menuView.render();
            Sandbox.eventNotify('customer:menu:set', {
                name: 'MenuLeft',
                el: menuView.$el,
                append: true
            });
        });
    });

    Sandbox.eventSubscribe('global:auth:status:inactive', function (data) {
        user.clear();
        if (menuView) {
            menuView.remove();
        }
    });

    Sandbox.eventSubscribe('global:page:signin', function (data) {
        var self = this;
        if (Auth.getUserID())
            return;
        require(['plugin/system/toolbox/js/view/signin'], function (SignIn) {
            var signin = new SignIn();
            signin.render();
            // debugger;
            Sandbox.eventNotify('global:content:render', {
                name: 'CommonBodyCenter',
                el: signin.$el
            });
        });
    });

    var routes = {
            "!/system/overview": "overview",
            "!/system/customers": "customers",
            "!/system/customer/edit/:id": "customerEdit",
            "!/system/customer/new": "customerCreate",
            "!/system/migrations": "migrations",
            "!/system/users": "users",
            "!/system/user/new": "userCreate",
            "!/system/user/edit/:id": "userEdit"
    };

    var Router = Backbone.Router.extend({
        routes: routes,

        urls: _(routes).invert(),

        initialize: function () {
            var self = this;
            if (APP.dfd.dashboard) {
                APP.dfd.dashboard.done(function () {
                    self.dashboard();
                });
            }
        },

        dashboard: function () {
            require(['plugin/system/toolbox/js/view/dashboard'], function (Dashboard) {
                // debugger;
                // create new view
                var dashboard = new Dashboard();
                dashboard.model.fetch();

                Sandbox.eventNotify('global:content:render', {
                    name: 'DashboardForPlugin_system',
                    el: dashboard.$el
                });
            });
        },

        users: function () {
            require(['plugin/system/toolbox/js/view/managerUsers'], function (Dashboard) {
                // debugger;
                // create new view
                var dashboard = new Dashboard();
                dashboard.model.fetch();

                Sandbox.eventNotify('global:content:render', {
                    name: 'DashboardForPlugin_system',
                    el: dashboard.$el
                });
            });
        }
    });

    return Router;

});