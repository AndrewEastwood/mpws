define("plugin/account/toolbox/js/router", [
    'default/js/lib/sandbox',
    'cmn_jquery',
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'plugin/account/common/js/model/account',
    'plugin/account/toolbox/js/view/buttonAccount',
    'default/js/lib/auth',
    'default/js/lib/cache',
], function (Sandbox, $, _, Backbone, Account, ViewButtonAccount, Auth, Cache) {

    // this is the account instance
    var account = new Account();

    Sandbox.eventSubscribe('global:auth:status:active', function (data) {
        var authAccountID = Auth.getAccountID();
        if (authAccountID) {
            account.set('ID', authAccountID);
            account.fetch();
            var buttonAccount = new ViewButtonAccount({
                model: account
            });
            buttonAccount.render();
            Sandbox.eventNotify('global:content:render', {
                name: 'TopMenuRight',
                el: buttonAccount.$el
            });
        }
    });

    Sandbox.eventSubscribe('global:auth:status:inactive', function (data) {
        account.clear();
    });

    Sandbox.eventSubscribe('global:page:signin', function (data) {
        var self = this;
        if (Auth.getAccountID())
            return;
        require(['plugin/account/toolbox/js/view/signin'], function (SignIn) {
            var signin = new SignIn();
            signin.render();
            // debugger;
            Sandbox.eventNotify('global:content:render', {
                name: 'Page',
                el: signin.$el
            });
        });
    });

    var Router = Backbone.Router.extend({
        routes: {
            "account/overview": "overview",
            "account/accounts": "accounts",
            "account/account/:id": "accounts"
        },

        initialize: function () {
            var self = this;
            Sandbox.eventSubscribe('plugin:dashboard:ready', function () {
                self.stats();
            });
        },

        stats: function () {
            require(['plugin/account/toolbox/js/view/stats'], function (Stats) {
                // debugger;
                // create new view
                var stats = new Stats();
                stats.model.fetch();

                Sandbox.eventNotify('global:content:render', {
                    name: 'CommonBodyCenter',
                    el: stats.$el,
                    append: true
                });
            });
        },

        overview: function () {
            Sandbox.eventNotify('global:menu:set-active', '.menu-shop-products');
            require(['plugin/shop/toolbox/js/view/productManager'], function (ProductManager) {
                // create new view
                var productManager = new ProductManager();
                productManager.render();

                Sandbox.eventNotify('global:content:render', {
                    name: 'CommonBodyCenter',
                    el: productManager.$el
                });

                // set page title
                Sandbox.eventNotify('global:content:render', {
                    name: 'CustomerPageName',
                    el: "Товари"
                });
            });
        },

        accounts: function (accountID) {
            Sandbox.eventNotify('global:menu:set-active', '.menu-shop-products');
            require(['plugin/shop/toolbox/js/view/productManager'], function (ProductManager) {
                // create new view
                var productManager = new ProductManager();
                productManager.render();

                if (accountID)
                    productManager.showAccount(accountID);

                Sandbox.eventNotify('global:content:render', {
                    name: 'CommonBodyCenter',
                    el: productManager.$el
                });

                // set page title
                Sandbox.eventNotify('global:content:render', {
                    name: 'CustomerPageName',
                    el: "Товари"
                });
            });
        }
    });

    return Router;

});