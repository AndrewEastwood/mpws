define("plugin/account/toolbox/js/router", [
    'default/js/lib/sandbox',
    'cmn_jquery',
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'default/js/lib/auth',
    // 'plugin/account/common/js/lib/auth',
    "default/js/lib/cache",
    // 'plugin/account/toolbox/js/view/menu'
], function (Sandbox, $, _, Backbone, Auth, Cache) {



    // var renderCompleteSent = false;

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

    // Sandbox.eventSubscribe('global:ajax:responce', function (data) {
    //     if (data && data.authenticated && !renderCompleteSent) {
    //         renderCompleteSent = true;
    //         Sandbox.eventNotify('plugin:account:complete');
    //     }
    // });

    Sandbox.eventSubscribe('global:page:signout', function (data) {
        Auth.signout();
    });

    // Sandbox.eventSubscribe('global:route', function (data) {
    //     Auth.getStatus();
    // });

    // // inject into toolbox layout another plugin's content
    // // this is a bridge between layout an other plugins
    // // it is better to do render through these events
    // Sandbox.eventSubscribe('plugin:toolbox:page:show', function (options) {
    //     Sandbox.eventNotify('global:content:render', _.extend({}, options, {
    //         name: 'CommonBodyCenter',
    //     }));
    // });

    // Sandbox.eventSubscribe('plugin:toolbox:menu:display', function (options) {
    //     Sandbox.eventNotify('global:content:render', _.extend({}, options, {
    //         name: options.name || 'PluginToolboxMenuList',
    //     }));
    //     // sort nodes
    //     // debugger;
    //     // $('#toolbox-menu-ID').tsort({place:'top'});
    //     Sandbox.eventNotify('global:menu:set-active');
    // });
});