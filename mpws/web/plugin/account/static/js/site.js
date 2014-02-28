define("plugin/account/js/site", [
    'customer/js/site',
    'cmn_jquery',
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'plugin/account/js/view/menuSite',
    'plugin/account/js/model/account',
    'default/js/lib/cache'
], function (Site, $, _, Backbone, MenuSite, ModelAccount, Cache) {

    var account = new ModelAccount();

    var Router = Backbone.Router.extend({
        routes: {
            // "account/login": "login",
            "account/logout": "logout",
            "account/profile": "profile",
            "account/create": "create",
        },

        initialize: function () {

            MenuSite.render();
        },

        create: function () {

            Site.showBreadcrumbLocation();

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

        logout: function () {},

        profile: function () {},

    });

    return Router;

});