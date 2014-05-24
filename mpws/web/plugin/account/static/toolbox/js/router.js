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
        debugger;
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

            // Sandbox.eventNotify('plugin:toolbox:logout');
    });


    Sandbox.eventSubscribe('plugin:account:status:received', function (data) {

        debugger;
        if (data.account === null) {
            if (Backbone.history.fragment !== "signin")
                Backbone.history.navigate("signin", true);
        } else {
            if (Backbone.history.fragment === "signin") {
                var _location = Cache.getFromLocalStorage("location") || '';
                Backbone.history.navigate(_location, true);
            }
        }
        // debugger;
        // save location
        if (Backbone.history.fragment !== "signout" && Backbone.history.fragment !== "signin")
            Cache.saveInLocalStorage("location", window.location.hash);
        // if (!renderCompleteSent) {
        //     renderCompleteSent = true;
        //     Sandbox.eventNotify('plugin:toolbox:render:complete');
        // }
    });

    Auth.getStatus();
});