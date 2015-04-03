define([
    'jquery',
    'underscore',
    'backbone',
    'plugins/system/toolbox/js/view/menu',
    'plugins/system/toolbox/js/view/buttonUser',
    'plugins/system/toolbox/js/view/dropdownCustomerSwitcher',

    'plugins/system/toolbox/js/view/signin',
    'plugins/system/toolbox/js/view/dashboard',
    'plugins/system/toolbox/js/view/managerCustomers',
    'plugins/system/toolbox/js/view/editCustomer',
    'plugins/system/common/js/view/editUser',
    'plugins/system/toolbox/js/view/managerUsers',

    'plugins/system/common/js/model/user',
    'auth',
    'cachejs',
], function ($, _, Backbone,
    ViewMenu,
    ViewButtonUser,
    ViewCustomerSwitcher,
    ViewSignIn,
    ViewDashboard,
    ViewManagerCustomers,
    ViewEditCustomer,
    ViewEditUser,
    ViewManagerUsers,

    ModelUser, Auth, Cache) {

    // this is the user instance
    // var menuView = null;
    var user = new ModelUser();
    // APP.dfd.systemDashboard = new $.Deferred();
    // APP.dfd.systemMenu = new $.Deferred();
    // Backbone.on('auth:info auth:registered', function () {
    //     debugger
    // });

    // Auth.on('registered', function () {
    //     var authUserID = Auth.getUserID();
    //     if (authUserID) {
    //         user.set('ID', authUserID);
    //         user.fetch();
    //         require(['plugins/system/toolbox/js/view/buttonUser'], function (ViewButtonUser) {
    //             var buttonUser = new ViewButtonUser({
    //                 model: user
    //             });
    //             buttonUser.render();
    //             APP.Sandbox.eventNotify('global:content:render', {
    //                 name: 'TopMenuRight',
    //                 el: buttonUser.$el
    //             });
    //         });
    //     }
    //     // show this menu when user is can manage
    //     // debugger
    //     require(['plugins/system/toolbox/js/view/menu', 'plugins/system/toolbox/js/view/dropdownCustomerSwitcher'], function (ViewMenu, ViewCustomerSwitcher) {
    //         menuView = new ViewMenu({
    //             model: user
    //         });
    //         menuView.render();
    //         APP.Sandbox.eventNotify('global:content:render', {
    //             name: 'MenuForPlugin_system',
    //             el: menuView.$el
    //         });

    //         if (APP.config.USER.p_CanMaintain) {
    //             customerSwitcherView = new ViewCustomerSwitcher();
    //             customerSwitcherView.render();
    //             APP.Sandbox.eventNotify('global:content:render', {
    //                 name: 'TopMenuLeft',
    //                 el: customerSwitcherView.$el,
    //                 append: true
    //             });
    //         } else {
    //             if (customerSwitcherView) {
    //                 customerSwitcherView.remove();
    //                 customerSwitcherView = null;
    //             }
    //         }
    //     });
    // });

    // Auth.on('guest', function () {
    //     user.clear();
    //     if (menuView) {
    //         menuView.remove();
    //     }
    // });

    // Auth.getStatus();

    // var routes = {
    //     "!/system/customers": "customersList",
    //     "!/system/customers/:status": "customersListByStatus",
    //     "!/system/customer/edit/:id": "customerEdit",
    //     "!/system/customer/new": "customerCreate",
    //     "!/system/migrations": "migrations",
    //     "!/system/users": "usersList",
    //     "!/system/users/:status": "usersListByStatus",
    //     "!/system/user/edit/:id": "userEdit",
    //     "!/system/user/new": "userCreate"
    // };

    var View = Backbone.View.extend({
        // routes: routes,
        urls: {},
        dfdInitialize: function (callback, options) {
            // configure plugin
            this.options = options || {};
            this.urls = options && options.urls || {};
            // fetch data
            callback();
        },

        // urls: _(routes).invert(),

        // initialize: function () {
        //     var self = this;
        //     APP.Sandbox.eventSubscribe('global:page:index', function () {
        //         self.dashboard();
        //     });
        //     APP.Sandbox.eventSubscribe('global:page:signin', function () {
        //         self.signin();
        //     });
        //     // if (APP.dfd.dashboard) {
        //     //     APP.dfd.dashboard.done(function () {
        //     //         self.dashboard();
        //     //     });
        //     // }
        // },


        menu: function (options) {
            var menu = new ViewMenu(_.extend({}, options || {}, {
                model: user
            }));
            menu.render();
            return menu;
        },
        widgetCustomerSwitcher: function () {
            var customerSwitcherView = null;
            if (APP.config.USER.p_CanMaintain) {
                customerSwitcherView = new ViewCustomerSwitcher();
                customerSwitcherView.render();
            }
            return customerSwitcherView;
        },
        widgetUserButton: function () {
            var buttonUser = null;
            var authUserID = Auth.getUserID();
            if (authUserID) {
                user.set('ID', authUserID);
                user.fetch();
                buttonUser = new ViewButtonUser({
                    model: user
                });
                buttonUser.render();
            }
            return buttonUser;
        },

        signin: function () {
            // if (Auth.getUserID()) {
            //     return;
            // }
            var signin = new ViewSignIn();
            signin.render();
            return signin;
        },
        dashboard: function () {
            // if (!Auth.getUserID()) {
            //     return;
            // }
            // debugger
            // create new view
            // debugger
            var dashboard = new ViewDashboard();
            dashboard.model.fetch();
            return dashboard;
        },

        customersList: function () {
            if (!APP.config.USER.p_CanMaintain) {
                // Backbone.history.navigate(this.urls.dashboard, true);
                return null;
            }
            // debugger;
            // create new view
            var viewManagerCustomers = new ViewManagerCustomers();
            viewManagerCustomers.collection.fetch({reset: true});
            return viewManagerCustomers;
        },

        customersListByStatus: function (status) {
            if (!APP.config.USER.p_CanMaintain) {
                // Backbone.history.navigate(this.urls.dashboard, true);
                return null;
            }
            // debugger;
            // create new view
            var options = status ? {
                status: status
            } : {};
            var viewManagerCustomers = new ViewManagerCustomers(options);
            viewManagerCustomers.collection.fetch({reset: true});
            return viewManagerCustomers;
        },

        customerEdit: function (id) {
            // debugger;
            // create new view
            var viewEditCustomer = new ViewEditCustomer();
            viewEditCustomer.model.set('ID', id, {silent: true});
            viewEditCustomer.model.fetch({reset: true});
            return viewEditCustomer;
        },

        customerCreate: function () {
            if (!APP.config.USER.p_CanMaintain) {
                Backbone.history.navigate(this.urls.dashboard, true);
                return;
            }
            // create new view
            var viewEditCustomer = new ViewEditCustomer();
            viewEditCustomer.render();
            return viewEditCustomer;
        },

        userEdit: function (id) {
            // debugger;
            // create new view
            var viewEditUser = new ViewEditUser();
            viewEditUser.model.set('ID', id, {silent: true});
            viewEditUser.model.fetch({reset: true});
            return viewEditUser;
        },

        userCreate: function (id) {
            // debugger;
            // create new view
            var viewEditUser = new ViewEditUser();
            viewEditUser.render();
            return viewEditUser;
        },

        usersList: function () {
            var viewManagerUsers = new ViewManagerUsers();
            viewManagerUsers.collection.fetch({reset: true});
            return viewManagerUsers;
        },

        usersListByStatus: function (status) {
            // debugger;
            // create new view
            var options = status ? {
                status: status
            } : {};
            var viewManagerUsers = new ViewManagerUsers(options);
            viewManagerUsers.collection.fetch({reset: true});
            return viewManagerUsers;
        }
    });

    return View;

});