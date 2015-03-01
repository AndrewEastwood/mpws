define([
    'sandbox',
    'jquery',
    'underscore',
    'backbone',
    'plugins/system/common/js/model/user',
    'auth',
    'cachejs',
], function (Sandbox, $, _, Backbone, User, Auth, Cache) {

    // this is the user instance
    var menuView = null;
    var user = new User();
    // APP.dfd.systemDashboard = new $.Deferred();
    // APP.dfd.systemMenu = new $.Deferred();
    // Backbone.on('auth:info auth:registered', function () {
    //     debugger
    // });

    Auth.on('registered', function () {
        var authUserID = Auth.getUserID();
        if (authUserID) {
            user.set('ID', authUserID);
            user.fetch();
            require(['plugins/system/toolbox/js/view/buttonUser'], function (ViewButtonUser) {
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
        // debugger
        require(['plugins/system/toolbox/js/view/menu'], function (ViewMenu) {
            menuView = new ViewMenu({
                model: user
            });
            menuView.render();
            Sandbox.eventNotify('global:content:render', {
                name: 'MenuForPlugin_system',
                el: menuView.$el
            });
        });
    });

    Auth.on('guest', function () {
        user.clear();
        if (menuView) {
            menuView.remove();
        }
    });

    Auth.getStatus();

    var routes = {
        "!/system/customers": "customerList",
        "!/system/customer/:status": "customerListByStatus",
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
            Sandbox.eventSubscribe('global:page:index', function () {
                self.dashboard();
            });
            Sandbox.eventSubscribe('global:page:signin', function () {
                self.signin();
            });
            // if (APP.dfd.dashboard) {
            //     APP.dfd.dashboard.done(function () {
            //         self.dashboard();
            //     });
            // }
        },

        signin: function () {
            if (Auth.getUserID()) {
                return;
            }
            require(['plugins/system/toolbox/js/view/signin'], function (SignIn) {
                var signin = new SignIn();
                signin.render();
                // debugger;
                Sandbox.eventNotify('global:content:render', {
                    name: 'CommonBodyCenter',
                    el: signin.$el
                });
            });
        },
        dashboard: function () {
            if (!Auth.getUserID()) {
                return;
            }
            // debugger
            require(['plugins/system/toolbox/js/view/dashboard'], function (Dashboard) {
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

        customerList: function () {
            require(['plugins/system/toolbox/js/view/managerCustomers'], function (ViewManagerCustomers) {
                // debugger;
                // create new view
                var viewManagerCustomers = new ViewManagerCustomers();
                viewManagerCustomers.collection.fetch({reset: true});
                Sandbox.eventNotify('global:content:render', {
                    name: 'CommonBodyCenter',
                    el: viewManagerCustomers.$el
                });
            });
        },

        customerListByStatus: function (status) {
            require(['plugins/system/toolbox/js/view/managerCustomers'], function (ViewManagerCustomers) {
                // debugger;
                // create new view
                var options = status ? {
                    status: status
                } : {};
                var viewManagerCustomers = new ViewManagerCustomers(options);
                viewManagerCustomers.collection.fetch({reset: true});
                Sandbox.eventNotify('global:content:render', {
                    name: 'CommonBodyCenter',
                    el: viewManagerCustomers.$el
                });
            });
        },

        customerEdit: function (id) {
            require(['plugins/system/toolbox/js/view/editCustomer'], function (ViewEditCustomer) {
                // debugger;
                // create new view
                var viewEditCustomer = new ViewEditCustomer();
                viewEditCustomer.model.set('ID', id, {silent: true});
                viewEditCustomer.model.fetch({reset: true});
                Sandbox.eventNotify('global:content:render', {
                    name: 'CommonBodyCenter',
                    el: viewEditCustomer.$el
                });
            });
        },

        customerCreate: function () {
            require(['plugins/system/toolbox/js/view/editCustomer'], function (ViewEditCustomer) {
                // debugger;
                // create new view
                var viewEditCustomer = new ViewEditCustomer();
                viewEditCustomer.model.fetch({reset: true});
                Sandbox.eventNotify('global:content:render', {
                    name: 'CommonBodyCenter',
                    el: viewEditCustomer.$el
                });
            });
        },

        users: function () {
            require(['plugins/system/toolbox/js/view/listUsers'], function (Dashboard) {
                // debugger;
                // create new view
                var dashboard = new Dashboard();
                dashboard.model.fetch();
                Sandbox.eventNotify('global:content:render', {
                    name: 'CommonBodyCenter',
                    el: dashboard.$el
                });
            });
        }
    });

    return Router;

});