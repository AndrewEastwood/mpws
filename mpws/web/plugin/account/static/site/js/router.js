define("plugin/account/site/js/router", [
    'default/js/lib/sandbox',
    'default/js/lib/backbone',
    'plugin/account/site/js/view/menu',
    'default/js/lib/auth',
    'plugin/account/common/js/model/account'
    // 'cmn_jquery',
    // 'default/js/lib/underscore',
    // 'default/js/lib/cache',
    // 'plugin/account/site/js/view/menu',
], function (Sandbox, Backbone, SiteMenu, Auth, Account, $, _, Cache) {
    var account = new Account();
    SiteMenu({
        account: account
    });
    // order.fetch();

    // Auth.getStatus();



    Sandbox.eventSubscribe('global:auth:status:active', function (data) {
        debugger;
        account.id = data.id;
        account.fetch();
    });


    Sandbox.eventSubscribe('global:auth:status:inactive', function (data) {
        
    });


    return Backbone.Router.extend({});

    Sandbox.eventSubscribe('plugin:account:signed:out', function() {
        if (Backbone.history.fragment.match(/^account/))
            Backbone.history.navigate("", {trigger: true});
        else
            Backbone.history.loadUrl(Backbone.history.fragment);
    });

    Sandbox.eventSubscribe('plugin:account:signed:in', function() {
        if (Cache.getObject('AccountProfileID') && 
            (Backbone.history.fragment === "" || Backbone.history.fragment.match(/^account/))
        ) {
            Backbone.history.navigate("account/profile", {trigger: true});
        }
    });

    var Router = Backbone.Router.extend({
        routes: {
            "account/profile": "profile",
            "account/create": "create",
            "account/password": "password",
            "account/edit": "edit",
            "account/addresses": "addresses",
            "account/delete": "delete"
        },

        initialize: function () {
            // debugger;
            var self = this;

            Sandbox.eventSubscribe('plugin:account:profile:show', function(pageContent) {
                self.showProfileToolbar(pageContent);
            });
        },

        create: function () {

            Sandbox.eventNotify('global:breadcrumb:show');

            if (Cache.hasObject('AccountProfileID')) {
                Backbone.history.navigate("account/profile", {trigger: true});
                return;
            }

            require(['plugin/account/site/js/view/accountCreate'], function (AccountCreate) {
                // using this wrapper to cleanup previous view and create new one
                Cache.withObject('AccountCreate', function (cachedView) {
                    // debugger;
                    // remove previous view
                    if (cachedView && cachedView.remove)
                        cachedView.remove();

                    // create new view
                    var accountCreate = new AccountCreate();
                    // Site.placeholders.account.pageProfileCreate.html(accountCreate.el);
                    Sandbox.eventNotify('global:content:render', {
                        name: 'AccountProfileCreate',
                        el: accountCreate.el
                    });
                    accountCreate.fetchAndRender();

                    // return view object to pass it into this function at next invocation
                    return accountCreate;
                });
            });

        },

        profile: function () {
            if (!Cache.hasObject('AccountProfileID')) {
                Backbone.history.navigate("", {trigger: true});
                return;
            }

            Sandbox.eventNotify('global:breadcrumb:show');

            var self = this;
            // this.showProfileToolbar();

            // Sandbox.eventSubscribe('view:AccountProfile', function (view) {
                require(['plugin/account/site/js/view/accountProfileOverview'], function (AccountProfileOverview) {
                    // using this wrapper to cleanup previous view and create new one
                    Cache.withObject('AccountProfileOverview', function (cachedView) {
                        // debugger;
                        // remove previous view
                        if (cachedView && cachedView.remove)
                            cachedView.remove();

                        // create new view
                        var accountProfileOverview = new AccountProfileOverview();
                        // view.setPagePlaceholder(accountProfileOverview.el);
                        self.showProfileToolbar(accountProfileOverview.el);
                        accountProfileOverview.fetchAndRender({
                            action: 'status'
                        });
                        // Sandbox.eventNotify('global:content:render', {
                        //     name: 'AccountProfileOverview',
                        //     el: accountProfileOverview.el
                        // });

                        // return view object to pass it into this function at next invocation
                        return accountProfileOverview;
                    });
                });
            // });
        },

        password: function () {
            if (!Cache.hasObject('AccountProfileID')) {
                Backbone.history.navigate("", {trigger: true});
                return;
            }

            Sandbox.eventNotify('global:breadcrumb:show');

            var self = this;
            // this.showProfileToolbar();

            // Sandbox.eventSubscribe('view:AccountProfile', function (view) {
                require(['plugin/account/site/js/view/accountProfilePassword'], function (AccountProfilePassword) {
                    // using this wrapper to cleanup previous view and create new one
                    Cache.withObject('AccountProfilePassword', function (cachedView) {
                        // debugger;
                        // remove previous view
                        if (cachedView && cachedView.remove)
                            cachedView.remove();

                        // create new view
                        var accountProfilePassword = new AccountProfilePassword();
                        // view.setPagePlaceholder(accountProfilePassword.el);
                        self.showProfileToolbar(accountProfilePassword.el);
                        accountProfilePassword.fetchAndRender({
                            action: 'status'
                        });

                        // return view object to pass it into this function at next invocation
                        return accountProfilePassword;
                    });
                });
            // });

        },

        edit: function () {
            if (!Cache.hasObject('AccountProfileID')) {
                Backbone.history.navigate("", {trigger: true});
                return;
            }

            Sandbox.eventNotify('global:breadcrumb:show');

            var self = this;
            // this.showProfileToolbar();

            // Sandbox.eventSubscribe('view:AccountProfile', function (view) {
                require(['plugin/account/site/js/view/accountProfileEdit'], function (AccountProfileEdit) {
                    // using this wrapper to cleanup previous view and create new one
                    Cache.withObject('AccountProfileEdit', function (cachedView) {
                        // debugger;
                        // remove previous view
                        if (cachedView && cachedView.remove)
                            cachedView.remove();

                        // create new view
                        var accountProfileEdit = new AccountProfileEdit();
                        // self.setPagePlaceholder(accountProfileEdit.el);
                        self.showProfileToolbar(accountProfileEdit.el);
                        accountProfileEdit.fetchAndRender({
                            action: 'status'
                        });

                        // return view object to pass it into this function at next invocation
                        return accountProfileEdit;
                    });
                });
            // });

        },

        addresses: function () {
            if (!Cache.hasObject('AccountProfileID')) {
                Backbone.history.navigate("", {trigger: true});
                return;
            }

            var self = this;

            Sandbox.eventNotify('global:breadcrumb:show');

            require(['plugin/account/site/js/view/accountProfileAddresses'], function (AccountProfileAddresses) {
                // using this wrapper to cleanup previous view and create new one
                Cache.withObject('AccountProfileAddresses', function (cachedView) {
                    // debugger;
                    // remove previous view
                    if (cachedView && cachedView.remove)
                        cachedView.remove();

                    // create new view
                    var accountProfileAddresses = new AccountProfileAddresses();
                    // view.setPagePlaceholder(accountProfileAddresses.el);
                    self.showProfileToolbar(accountProfileAddresses.el);
                    accountProfileAddresses.fetchAndRender({
                        action: 'status'
                    });

                    // return view object to pass it into this function at next invocation
                    return accountProfileAddresses;
                });
            });

        },

        delete: function () {
            if (!Cache.hasObject('AccountProfileID')) {
                Backbone.history.navigate("", {trigger: true});
                return;
            }

            var self = this;

            Sandbox.eventNotify('global:breadcrumb:show');

            require(['plugin/account/site/js/view/accountProfileDelete'], function (AccountProfileDelete) {
                // using this wrapper to cleanup previous view and create new one
                Cache.withObject('AccountProfileDelete', function (cachedView) {
                    // debugger;
                    // remove previous view
                    if (cachedView && cachedView.remove)
                        cachedView.remove();

                    // create new viewl
                    var accountProfileDelete = new AccountProfileDelete();
                    // view.setPagePlaceholder(accountProfileDelete.el);
                    self.showProfileToolbar(accountProfileDelete.el);
                    accountProfileDelete.fetchAndRender({
                        action: 'status'
                    });

                    // return view object to pass it into this function at next invocation
                    return accountProfileDelete;
                });
            });

        },

        showProfileToolbar: function (pageContent) {
            require(['plugin/account/site/js/view/accountProfile'], function (AccountProfile) {
                // using this wrapper to cleanup previous view and create new one
                Cache.withObject('AccountProfile', function (cachedView) {
                    // debugger;
                    // remove previous view
                    if (cachedView && cachedView.remove)
                        cachedView.remove();

                    // create new view
                    var accountProfile = new AccountProfile();
                    // Site.placeholders.account.pageProfile.html(accountProfile.el);
                    accountProfile.on('mview:renderComplete', function () {
                        accountProfile.setPagePlaceholder(pageContent);
                    });
                    accountProfile.fetchAndRender({
                        action: 'status'
                    });
                    Sandbox.eventNotify('global:content:render', {
                        name: 'AccountProfile',
                        el: accountProfile.el
                    });

                    // return view object to pass it into this function at next invocation
                    return accountProfile;
                });
            });
        }

    });

    return Router;

});