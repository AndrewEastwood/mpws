define("plugin/toolbox/js/toolbox", [
    'default/js/lib/sandbox',
    'customer/js/site',
    'cmn_jquery',
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'default/js/lib/cache',
    'plugin/toolbox/js/view/bridge'
], function (Sandbox, Site, $, _, Backbone, Cache, Bridge) {

    var bridge = new Bridge();

    // get user status when page starts
    Sandbox.eventSubscribe('global:loader:complete', function (status) {
        Sandbox.eventNotify('plugin:toolbox:status');
    });

    // get user status with each page route action
    Sandbox.eventSubscribe('global:route', function (hash) {
        if (hash !== "signin")
            Sandbox.eventNotify('plugin:toolbox:status');
    });

    // handle user status
    Sandbox.eventSubscribe('plugin:toolbox:status:received', function (status) {
        // do redirecto to index page when user is signed in
        if (status && Backbone.history.fragment === "signin")
            Backbone.history.navigate('', true);
    });

    // when user is signein do smth
    Sandbox.eventSubscribe('plugin:toolbox:signed:in', function (status) {
        bridge.render();
        Sandbox.eventNotify('global:content:render', {
            name: 'Bridge',
            el: bridge.el
        });
        Backbone.history.navigate('', true);
    });

    // redirect user to the signin page
    Sandbox.eventSubscribe('plugin:toolbox:signed:out', function (status) {
        Backbone.history.navigate('signin', true);
    });

    // inject into toolbox layout another plugin's content
    Sandbox.eventSubscribe('plugin:toolbox:page:show', function (page) {
        bridge.setPagePlaceholder(page);
    });

    var Router = Backbone.Router.extend({
        routes: {
            "signin": "signin",
            "signout": "signout",
        },

        signin: function () {
            // debugger;
            var self = this;
            require(['plugin/toolbox/js/view/signin'], function (SignIn) {
                // using this wrapper to cleanup previous view and create new one
                Cache.withObject('SignIn', function (cachedView) {
                    // debugger;
                    // remove previous view
                    if (cachedView && cachedView.remove)
                        cachedView.remove();

                    // create new view
                    var signin = new SignIn();
                    signin.render();
                    Sandbox.eventNotify('global:content:render', {
                        name: 'SignIn',
                        el: signin.el
                    });

                    // return view object to pass it into this function at next invocation
                    return signin;
                });
            });
        },

        signout: function () {
            Backbone.history.navigate('signin', true);;
        }
    });

    return Router;
});