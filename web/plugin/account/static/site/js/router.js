define("plugin/account/site/js/router", [
    'default/js/lib/sandbox',
    'default/js/lib/backbone',
    'plugin/account/site/js/view/menu',
    'default/js/lib/auth',
    'plugin/account/common/js/model/account',
    'default/js/lib/cache'
], function (Sandbox, Backbone, SiteMenu, Auth, Account, Cache, $, _) {

    // this is the account instance
    var account = new Account();

    SiteMenu({
        account: account
    });

    Cache.setObject('account:model', account);

    // account.on('change', function () {
    //     Sandbox.eventNotify('plugin:account:model:change', account);
    // });

    Auth.on('registered', function () {
        _navigateToAccountIfAuthorizedFn();
    });

    Auth.on('guest', function () {
        _navigateToHomeIfNotAuthorizedFn();
    });

    var _navigateToHomeIfNotAuthorizedFn = function () {
        var isAccountPage = /^account/.test(Backbone.history.fragment);
        var isSignUp = /^account\/create/.test(Backbone.history.fragment);
        if (!Auth.getAccountID() && !isSignUp && isAccountPage) {
            Backbone.history.navigate("", true);
            return true;
        }
        return false;
    }

    var _navigateToAccountIfAuthorizedFn = function () {
        var isAccountPage = /^account/.test(Backbone.history.fragment);
        var isSignUp = /^account\/create/.test(Backbone.history.fragment);
        if (Auth.getAccountID() && isSignUp) {
            Backbone.history.navigate("account/summary", true);
            return true;
        }
        return false;
    }

    Sandbox.eventSubscribe('global:auth:status:active', function (data) {
        var authAccountID = Auth.getAccountID();
        if (authAccountID) {
            account.set('ID', authAccountID);
            account.fetch();
        }
    });

    Sandbox.eventSubscribe('global:auth:status:inactive', function (data) {
        account.clear();
        _navigateToHomeIfNotAuthorizedFn();
    });

    var Router = Backbone.Router.extend({
        routes: {
            "account/summary": "summary",
            "account/create": "create",
            "account/password": "password",
            "account/edit": "edit",
            "account/addresses": "addresses",
            "account/delete": "delete"
        },

        initialize: function () {
            var self = this;
            Sandbox.eventSubscribe('plugin:account:profile:show', function(pageContent) {
                self.showProfileToolbar(pageContent);
            });
        },

        create: function () {
            if (_navigateToAccountIfAuthorizedFn())
                return;
            // Sandbox.eventNotify('global:breadcrumb:show');
            APP.getCustomer().setBreadcrumb();
            require(['plugin/account/site/js/view/accountCreate'], function (AccountCreate) {
                // create new view
                var accountCreate = new AccountCreate({
                    model: account
                });
                Sandbox.eventNotify('global:content:render', {
                    name: 'CommonBodyCenter',
                    el: accountCreate.el
                });
                accountCreate.render();
            });

        },

        summary: function () {
            if (_navigateToHomeIfNotAuthorizedFn())
                return;
            // Sandbox.eventNotify('global:breadcrumb:show');
            var self = this;
            APP.getCustomer().setBreadcrumb();
            require(['plugin/account/site/js/view/accountSummary'], function (AccountSummary) {
                var accountSummary = new AccountSummary({
                    model: account
                });
                accountSummary.render();
                self.showProfileToolbar(accountSummary.$el);
            });
        },

        password: function () {
            if (_navigateToHomeIfNotAuthorizedFn())
                return;
            // Sandbox.eventNotify('global:breadcrumb:show');
            var self = this;
            APP.getCustomer().setBreadcrumb();
            require(['plugin/account/site/js/view/accountProfilePassword'], function (AccountProfilePassword) {
                // create new view
                var accountProfilePassword = new AccountProfilePassword({
                    model: account
                });
                accountProfilePassword.render();
                self.showProfileToolbar(accountProfilePassword.$el);
            });
        },

        edit: function () {
            if (_navigateToHomeIfNotAuthorizedFn())
                return;
            // Sandbox.eventNotify('global:breadcrumb:show');
            var self = this;
            APP.getCustomer().setBreadcrumb();
            require(['plugin/account/site/js/view/accountEdit'], function (AccountEdit) {
                // create new view
                var accountEdit = new AccountEdit({
                    model: account
                });
                // self.setPagePlaceholder(accountEdit.el);
                self.showProfileToolbar(accountEdit.$el);
                accountEdit.render();
            });
        },

        addresses: function () {
            if (_navigateToHomeIfNotAuthorizedFn())
                return;
            var self = this;
            // Sandbox.eventNotify('global:breadcrumb:show');
            APP.getCustomer().setBreadcrumb();
            require(['plugin/account/site/js/view/accountProfileAddresses'], function (AccountProfileAddresses) {
                // create new view
                var accountProfileAddresses = new AccountProfileAddresses({
                    model: account
                });
                // view.setPagePlaceholder(accountProfileAddresses.el);
                self.showProfileToolbar(accountProfileAddresses.$el);
                accountProfileAddresses.render();
            });
        },

        delete: function () {
            if (_navigateToHomeIfNotAuthorizedFn())
                return;
            var self = this;
            // Sandbox.eventNotify('global:breadcrumb:show');
            APP.getCustomer().setBreadcrumb();
            require(['plugin/account/site/js/view/accountProfileDelete'], function (AccountProfileDelete) {
                // create new viewl
                var accountProfileDelete = new AccountProfileDelete();
                // view.setPagePlaceholder(accountProfileDelete.el);
                self.showProfileToolbar(accountProfileDelete.el);
                accountProfileDelete.fetchAndRender({
                    action: 'status'
                });
            });
        },

        showProfileToolbar: function (pageContent) {
            APP.getCustomer().setBreadcrumb();
            require(['plugin/account/site/js/view/accountHolder'], function (ViewAccountHolder) {
                // create new view
                var viewAccountHolder = new ViewAccountHolder({
                    model: account
                });
                Sandbox.eventNotify('global:content:render', {
                    name: 'CommonBodyCenter',
                    el: viewAccountHolder.$el
                });
                viewAccountHolder.render(pageContent);
            });
        }

    }, {
        account: account
    });

    return Router;

});