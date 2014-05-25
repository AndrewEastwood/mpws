define("plugin/account/toolbox/js/router", [
    'default/js/lib/sandbox',
    'cmn_jquery',
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'plugin/account/common/js/lib/auth',
    "default/js/lib/cache",
    'plugin/account/toolbox/js/view/menu'
], function (Sandbox, $, _, Backbone, Auth, Cache) {

    Sandbox.eventSubscribe('global:page:signin', function (data) {
        var self = this;
        require(['plugin/account/toolbox/js/view/signin'], function (SignIn) {
            // using this wrapper to cleanup previous view and create new one
            Cache.withObject('SignIn', function (cachedView) {
                // debugger;
                // remove previous view
                if (cachedView && cachedView.remove)
                    cachedView.remove();

                // create new view
                var signin = new SignIn();
                signin.render();
                // debugger;
                APP.commonElements.empty();
                Sandbox.eventNotify('global:content:render', {
                    name: 'CommonBodyCenter',
                    el: signin.el
                });

                // return view object to pass it into this function at next invocation
                return signin;
            });
        });
    });

    Sandbox.eventSubscribe('global:page:signout', function (data) {
        Auth.signout();
    });

    Sandbox.eventSubscribe('global:route', function (data) {
        if (Backbone.history.fragment !== 'signin' || Backbone.history.fragment !== 'signout') {
            Auth.getStatus();
        }
    });
});