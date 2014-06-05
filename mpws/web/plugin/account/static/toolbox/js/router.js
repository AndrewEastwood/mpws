define("plugin/account/toolbox/js/router", [
    'default/js/lib/sandbox',
    'cmn_jquery',
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'default/js/lib/auth',
    'default/js/lib/cache',
], function (Sandbox, $, _, Backbone, Auth, Cache) {

    Sandbox.eventSubscribe('global:page:signin', function (data) {
        var self = this;
        if (Auth.isAuthenticated)
            return;
        require(['plugin/account/toolbox/js/view/signin'], function (SignIn) {
            // using this wrapper to cleanup previous view and create new one
            Cache.withObject('SignIn', function (cachedView) {
                // debugger;
                // remove previous view
                if (cachedView && cachedView.remove)
                    cachedView.remove();
                // create new view
                APP.commonElements.empty();
                var signin = new SignIn();
                signin.render();
                // debugger;
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

    Sandbox.eventSubscribe('global:page:index', function () {
        Sandbox.eventNotify('global:content:render', {
            name: 'CommonBodyCenter',
            el: $('<hr size=10/><div>TEST!!!!!</div>'),
            append: true
        });
    });



});