define("plugin/account/js/site", [
    'default/js/lib/sandbox',
    'customer/js/site',
    'cmn_jquery',
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'plugin/account/js/view/menuSite',
    'default/js/lib/cache'
], function (Sandbox, Site, $, _, Backbone, MenuSite, Cache) {

    // var account = new ModelAccount();

    var Router = Backbone.Router.extend({
        routes: {
            // "account/login": "login",
            "account/logout": "logout",
            "account/profile": "profile",
            "account/create": "create",
            "account/settings": "settings",
        },

        initialize: function () {

            MenuSite.render();
        },

        create: function () {

            Site.showBreadcrumbLocation();

            Sandbox.eventSubscribe('account:signed:in', function() {
                if (Cache.getObject('AccountProfileID')) {
                    Backbone.history.navigate("account/profile", {trigger: true});
                }
            });

            Sandbox.eventSubscribe('account:signed:out', function() {
                // debugger;
                Backbone.history.navigate("", {trigger: true});
            });

            if (Cache.hasObject('AccountProfileID')) {
                Backbone.history.navigate("account/profile", {trigger: true});
                return;
            }

            require(['plugin/account/js/view/accountCreate'], function (AccountCreate) {
                // using this wrapper to cleanup previous view and create new one
                Cache.withObject('AccountCreate', function (cachedView) {
                    // debugger;
                    // remove previous view
                    if (cachedView && cachedView.remove)
                        cachedView.remove();

                    // create new view
                    var accountCreate = new AccountCreate();
                    Site.placeholders.account.pageLogin.html(accountCreate.el);
                    accountCreate.fetchAndRender();

                    // return view object to pass it into this function at next invocation
                    return accountCreate;
                });
            });

        },

        // login: function () {

        //     Site.showBreadcrumbLocation();

        //     require(['plugin/account/js/view/accountLogin'], function (AccountLogin) {
        //         // using this wrapper to cleanup previous view and create new one
        //         Cache.withObject('AccountLogin', function (cachedView) {
        //             // debugger;
        //             // remove previous view
        //             if (cachedView && cachedView.remove)
        //                 cachedView.remove();

        //             // create new view
        //             var accountLogin = new AccountLogin();
        //             Site.placeholders.account.pageLogin.html(accountLogin.el);
        //             accountLogin.fetchAndRender();

        //             // return view object to pass it into this function at next invocation
        //             return accountLogin;
        //         });
        //     });

        // },

        settings: function () {},

        logout: function () {},

        profile: function () {
            if (!Cache.hasObject('AccountProfileID')) {
                Backbone.history.navigate("account/create", {trigger: true});
                return;
            }

            Site.showBreadcrumbLocation();
            Site.placeholders.account.pageLogin.html("helo");
        },

    });

    return Router;

});