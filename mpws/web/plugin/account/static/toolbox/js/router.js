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

});