define([
    'jquery',
    'underscore',
    'backbone',
    'handlebars',
    'auth',
    'plugins/system/common/js/model/user',
    'plugins/system/site/js/view/menu',
    'plugins/system/site/js/view/authorize',
    'plugins/system/common/js/view/editUser'
], function ($, _, Backbone, Handlebars, Auth, ModelUser, ViewMenu, ViewAuthorize, ViewEditUser) {

    // return false;
    // this is the user instance
    // var user = new User();

    // SiteMenu({
    //     user: user
    // });

    // Cache.setObject('user:model', user);

    // // user.on('change', function () {
    // //     APP.Sandbox.eventNotify('plugin:user:model:change', user);
    // // });


    // var _navigateToHomeIfNotAuthorizedFn = function () {
    //     var isUserPage = /^user/.test(Backbone.history.fragment);
    //     var isSignUp = /^user\/create/.test(Backbone.history.fragment);
    //     if (!Auth.getUserID() && !isSignUp && isUserPage) {
    //         Backbone.history.navigate("", true);
    //         return true;
    //     }
    //     return false;
    // }

    // var _navigateToUserIfAuthorizedFn = function () {
    //     var isUserPage = /^user/.test(Backbone.history.fragment);
    //     var isSignUp = /^user\/create/.test(Backbone.history.fragment);
    //     if (Auth.getUserID() && isSignUp) {
    //         Backbone.history.navigate("user/summary", true);
    //         return true;
    //     }
    //     return false;
    // }

    var Router = Backbone.View.extend({
        urls: {},
        states: {},
        // routes: {
        //     "user/summary": "summary",
        //     "user/create": "create",
        //     "user/password": "password",
        //     "user/edit": "edit",
        //     "user/addresses": "addresses",
        //     "user/delete": "delete"
        // },

        // initialize: function () {
        //     var self = this;
        //     APP.Sandbox.eventSubscribe('plugin:user:profile:show', function(pageContent) {
        //         self.showToolbar(pageContent);
        //     });
        // },
        initialize: function (options, callback) {
            // configure plugin
            var that = this;
            this.options = options || {};
            this.urls = options && options.urls || {};
            this.states = options && options.states || {};
            // fetch data
            // callback();
            Auth.on('registered', function () {
                var user = ModelUser.getInstance();
                user.set('ID', Auth.getUserID());
                user.fetch();
                if (that.states.onRegisteredRoute) {
                    Backbone.history.navigate(that.states.onRegisteredRoute, true);
                }
                // _navigateToUserIfAuthorizedFn();
            });

            Auth.on('guest', function () {
                debugger
                var user = ModelUser.getInstance();
                user.clear();
                if (that.states.onGuestRoute) {
                    Backbone.history.navigate(that.states.onGuestRoute, true);
                }
                // _navigateToHomeIfNotAuthorizedFn();
            });
        },

        menu: function (options) {
            var menu = new ViewMenu(_.extend({}, options || {}));
            menu.render();
            return menu;
        },

        authorize: function () {
            // debugger;
            // create new view
            if (Auth.verifyStatus()) {
                if (that.states.onRegisteredRoute) {
                    Backbone.history.navigate(that.states.onRegisteredRoute, true);
                }
                return null;
            }
            var authorize = new ViewAuthorize();
            authorize.render();
            return authorize;
        },

        userPanel: function (/*id*/) {
            var that = this;
            // debugger;
            // create new view
            if (!Auth.verifyStatus()) {
                if (that.states.onGuestRoute) {
                    Backbone.history.navigate(that.states.onGuestRoute, true);
                }
                return null;
            }
            var viewEditUser = new ViewEditUser();
            viewEditUser.model.set('ID', Auth.getUserID(), {silent: true});
            viewEditUser.model.fetch({reset: true});
            return viewEditUser;
        },

        // signup: function () {
        //     // if (_navigateToUserIfAuthorizedFn())
        //     //     return;
        //     // // APP.Sandbox.eventNotify('global:breadcrumb:show');
        //     // APP.getCustomer().setBreadcrumb();
        //     // require(['plugins/system/site/js/view/userCreate'], function (UserCreate) {
        //     //     // create new view
        //     //     var userCreate = new UserCreate({
        //     //         model: user
        //     //     });
        //     //     APP.Sandbox.eventNotify('global:content:render', {
        //     //         name: 'CommonBodyCenter',
        //     //         el: userCreate.el
        //     //     });
        //     //     userCreate.render();
        //     // });

        // },



        // summary: function () {
        //     if (_navigateToHomeIfNotAuthorizedFn())
        //         return;
        //     // APP.Sandbox.eventNotify('global:breadcrumb:show');
        //     var self = this;
        //     APP.getCustomer().setBreadcrumb();
        //     require(['plugins/system/site/js/view/userSummary'], function (UserSummary) {
        //         var userSummary = new UserSummary({
        //             model: user
        //         });
        //         userSummary.render();
        //         self.showToolbar(userSummary.$el);
        //     });
        // },

        // password: function () {
        //     if (_navigateToHomeIfNotAuthorizedFn())
        //         return;
        //     // APP.Sandbox.eventNotify('global:breadcrumb:show');
        //     var self = this;
        //     APP.getCustomer().setBreadcrumb();
        //     require(['plugins/system/site/js/view/userPassword'], function (UserPassword) {
        //         // create new view
        //         var userPassword = new UserPassword({
        //             model: user
        //         });
        //         userPassword.render();
        //         self.showToolbar(userPassword.$el);
        //     });
        // },

        // edit: function () {
        //     if (_navigateToHomeIfNotAuthorizedFn())
        //         return;
        //     // APP.Sandbox.eventNotify('global:breadcrumb:show');
        //     var self = this;
        //     APP.getCustomer().setBreadcrumb();
        //     require(['plugins/system/site/js/view/userEdit'], function (UserEdit) {
        //         // create new view
        //         var userEdit = new UserEdit({
        //             model: user
        //         });
        //         // self.setPagePlaceholder(userEdit.el);
        //         self.showToolbar(userEdit.$el);
        //         userEdit.render();
        //     });
        // },

        // addresses: function () {
        //     if (_navigateToHomeIfNotAuthorizedFn())
        //         return;
        //     var self = this;
        //     // APP.Sandbox.eventNotify('global:breadcrumb:show');
        //     APP.getCustomer().setBreadcrumb();
        //     require(['plugins/system/site/js/view/userAddresses'], function (UserAddresses) {
        //         // create new view
        //         var userAddresses = new UserAddresses({
        //             model: user
        //         });
        //         // view.setPagePlaceholder(userAddresses.el);
        //         self.showToolbar(userAddresses.$el);
        //         userAddresses.render();
        //     });
        // },

        // delete: function () {
        //     if (_navigateToHomeIfNotAuthorizedFn())
        //         return;
        //     var self = this;
        //     // APP.Sandbox.eventNotify('global:breadcrumb:show');
        //     APP.getCustomer().setBreadcrumb();
        //     require(['plugins/system/site/js/view/userDelete'], function (UserDelete) {
        //         // create new view
        //         var userDelete = new UserDelete();
        //         // view.setPagePlaceholder(userDelete.el);
        //         self.showToolbar(userDelete.el);
        //         userDelete.fetchAndRender({
        //             action: 'status'
        //         });
        //     });
        // },

        // showToolbar: function (pageContent) {
        //     APP.getCustomer().setBreadcrumb();
        //     require(['plugins/system/site/js/view/userHolder'], function (ViewUserHolder) {
        //         // create new view
        //         var viewUserHolder = new ViewUserHolder({
        //             model: user
        //         });
        //         APP.Sandbox.eventNotify('global:content:render', {
        //             name: 'CommonBodyCenter',
        //             el: viewUserHolder.$el
        //         });
        //         viewUserHolder.render(pageContent);
        //     });
        // }

    });

    return Router;

});