define("plugin/account/js/site", [
    'customer/js/site',
    'cmn_jquery',
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'default/js/lib/cache'
], function (Site, $, _, Backbone, Cache, AccountLogin) {

    var Router = Backbone.Router.extend({
        routes: {
            "account/login": "login",
            "account/logout": "logout",
            "account/profile": "profile",
        },

        initialize: function () {
            Site.addMenuItem($('<a>').attr({
                href: '/#account/login'
            }).text('Login'));
        },

        login: function () {

            require(['default/js/view/accountLogin'], function (AccountLogin) {
                // using this wrapper to cleanup previous view and create new one
                Cache.withObject('AccountLogin', function (cachedView) {
                    // debugger;
                    // remove previous view
                    if (cachedView && cachedView.remove)
                        cachedView.remove();

                    // create new view
                    var accountLogin = new AccountLogin();
                    Site.placeholders.account.pageLogin.html(accountLogin.el);
                    accountLogin.fetchAndRender();

                    // return view object to pass it into this function at next invocation
                    return accountLogin;
                });
            });

        },

        logout: function () {},

        profile: function () {},

    });

    return Router;

});