define([
    'jquery',
    'underscore',
    'backbone',
    'plugins/system/common/js/model/user',
    'auth',
    'cachejs',
], function ($, _, Backbone, ModelUser, Auth, Cache) {

    // this is the user instance
    var menuView = null;
    var customerSwitcherView = null;
    var user = new ModelUser();
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
                APP.Sandbox.eventNotify('global:content:render', {
                    name: 'TopMenuRight',
                    el: buttonUser.$el
                });
            });
        }
        // show this menu when user is can manage
        // debugger
        require(['plugins/system/toolbox/js/view/menu', 'plugins/system/toolbox/js/view/dropdownCustomerSwitcher'], function (ViewMenu, ViewCustomerSwitcher) {
            menuView = new ViewMenu({
                model: user
            });
            menuView.render();
            APP.Sandbox.eventNotify('global:content:render', {
                name: 'MenuForPlugin_system',
                el: menuView.$el
            });

            if (APP.config.USER.p_CanMaintain) {
                customerSwitcherView = new ViewCustomerSwitcher();
                customerSwitcherView.render();
                APP.Sandbox.eventNotify('global:content:render', {
                    name: 'TopMenuLeft',
                    el: customerSwitcherView.$el,
                    append: true
                });
            } else {
                if (customerSwitcherView) {
                    customerSwitcherView.remove();
                    customerSwitcherView = null;
                }
            }
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
        "!/system/customers": "customersList",
        "!/system/customers/:status": "customersListByStatus",
        "!/system/customer/edit/:id": "customerEdit",
        "!/system/customer/new": "customerCreate",
        "!/system/migrations": "migrations",
        "!/system/users": "usersList",
        "!/system/users/:status": "usersListByStatus",
        "!/system/user/edit/:id": "userEdit",
        "!/system/user/new": "userCreate"
    };

    var Router = Backbone.Router.extend({
        routes: routes,

        urls: _(routes).invert(),

        initialize: function () {
            var self = this;
            APP.Sandbox.eventSubscribe('global:page:index', function () {
                self.dashboard();
            });
            APP.Sandbox.eventSubscribe('global:page:signin', function () {
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
                APP.Sandbox.eventNotify('global:content:render', {
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
                APP.Sandbox.eventNotify('global:content:render', {
                    name: 'DashboardForPlugin_system',
                    el: dashboard.$el
                });
            });
        },

        customersList: function () {
            if (!APP.config.USER.p_CanMaintain) {
                Backbone.history.navigate(this.urls.dashboard, true);
                return;
            }
            require(['plugins/system/toolbox/js/view/managerCustomers'], function (ViewManagerCustomers) {
                // debugger;
                // create new view
                var viewManagerCustomers = new ViewManagerCustomers();
                viewManagerCustomers.collection.fetch({reset: true});
                APP.Sandbox.eventNotify('global:content:render', {
                    name: 'CommonBodyCenter',
                    el: viewManagerCustomers.$el
                });
            });
        },

        customersListByStatus: function (status) {
            if (!APP.config.USER.p_CanMaintain) {
                Backbone.history.navigate(this.urls.dashboard, true);
                return;
            }
            require(['plugins/system/toolbox/js/view/managerCustomers'], function (ViewManagerCustomers) {
                // debugger;
                // create new view
                var options = status ? {
                    status: status
                } : {};
                var viewManagerCustomers = new ViewManagerCustomers(options);
                viewManagerCustomers.collection.fetch({reset: true});
                APP.Sandbox.eventNotify('global:content:render', {
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
                APP.Sandbox.eventNotify('global:content:render', {
                    name: 'CommonBodyCenter',
                    el: viewEditCustomer.$el
                });
            });
        },

        customerCreate: function () {
            if (!APP.config.USER.p_CanMaintain) {
                Backbone.history.navigate(this.urls.dashboard, true);
                return;
            }
            require(['plugins/system/toolbox/js/view/editCustomer'], function (ViewEditCustomer) {
                // create new view
                var viewEditCustomer = new ViewEditCustomer();
                viewEditCustomer.render();
                APP.Sandbox.eventNotify('global:content:render', {
                    name: 'CommonBodyCenter',
                    el: viewEditCustomer.$el
                });
            });
        },

        userEdit: function (id) {
            require(['plugins/system/common/js/view/editUser'], function (ViewEditUser) {
                // debugger;
                // create new view
                var viewEditUser = new ViewEditUser();
                viewEditUser.model.set('ID', id, {silent: true});
                viewEditUser.model.fetch({reset: true});
                APP.Sandbox.eventNotify('global:content:render', {
                    name: 'CommonBodyCenter',
                    el: viewEditUser.$el
                });
            });
        },

        userCreate: function (id) {
            require(['plugins/system/common/js/view/editUser'], function (ViewEditUser) {
                // debugger;
                // create new view
                var viewEditUser = new ViewEditUser();
                viewEditUser.render();
                APP.Sandbox.eventNotify('global:content:render', {
                    name: 'CommonBodyCenter',
                    el: viewEditUser.$el
                });
            });
        },

        usersList: function () {
            require(['plugins/system/toolbox/js/view/managerUsers'], function (ViewManagerUsers) {
                var viewManagerUsers = new ViewManagerUsers();
                viewManagerUsers.collection.fetch({reset: true});
                APP.Sandbox.eventNotify('global:content:render', {
                    name: 'CommonBodyCenter',
                    el: viewManagerUsers.$el
                });
            });
        },

        usersListByStatus: function (status) {
            require(['plugins/system/toolbox/js/view/managerUsers'], function (ViewManagerUsers) {
                // debugger;
                // create new view
                var options = status ? {
                    status: status
                } : {};
                var viewManagerUsers = new ViewManagerUsers(options);
                viewManagerUsers.collection.fetch({reset: true});
                APP.Sandbox.eventNotify('global:content:render', {
                    name: 'CommonBodyCenter',
                    el: viewManagerUsers.$el
                });
            });
        }
    });

    return Router;

});