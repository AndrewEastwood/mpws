define("plugin/toolbox/toolbox/js/router", [
    'default/js/lib/sandbox',
    'cmn_jquery',
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'default/js/lib/cache',
    'plugin/toolbox/toolbox/js/view/menu',
    'plugin/toolbox/toolbox/js/model/bridge',
    // 'plugin/toolbox/toolbox/js/view/bridge', // need to register all auth events
], function (Sandbox, $, _, Backbone, Cache, Menu, Bridge) {

    var isLoading = true;

    // get user status when page starts
    Sandbox.eventSubscribe('global:loader:complete', function (status) {
        Sandbox.eventNotify('plugin:toolbox:status');
    });

    Sandbox.eventSubscribe('global:session:expired', function (error) {
        // Backbone.history.navigate('signin', true);

        if (error === "InvalidPublicTokenKey") {
            document.location.reload();
            throw "Session timeout";
        }

        // debugger;
        if (_(["LoginRequired", "AccessDenied"]).indexOf(error) > -1  && Backbone.history.fragment !== "signin") {
            Sandbox.eventNotify('plugin:toolbox:logout');
            return;
        }

        throw error;
    });

    // get user status with each page route action
    Sandbox.eventSubscribe('global:route', function (hash) {
        if (hash !== "signin")
            Sandbox.eventNotify('plugin:toolbox:status');
    });

    // handle user status
    Sandbox.eventSubscribe('plugin:toolbox:status:received', function (status) {
        debugger;
        // do redirecto to index page when user is signed in
        if (status) {
            if (Backbone.history.fragment === "signin") {
                var _location = localStorage.location || '';
                Backbone.history.navigate(_location, true);
            }
            // save location
            if (localStorage && Backbone.history.fragment !== "signout" && Backbone.history.fragment !== "signin") {
                localStorage.setItem('location', window.location.hash);
            }
            if (isLoading) {
                isLoading = false;
                Sandbox.eventSubscribe('global:loader:complete', function (status) {
                    Sandbox.eventNotify('plugin:toolbox:render:complete');
                });
            }
        } else {
            Backbone.history.navigate('signin', true);
        }
    });

    // when user is signed in do smth
    Sandbox.eventSubscribe('plugin:toolbox:signed:in', function (status) {
        var _location = localStorage.location || '';
        Backbone.history.navigate(_location, true);
    });

    // redirect user to the signin page
    Sandbox.eventSubscribe('plugin:toolbox:signed:out', function (status) {
        Backbone.history.navigate('signin', true);
    });

    // create new view
    // var bridge = new Bridge();

    // inject into toolbox layout another plugin's content
    Sandbox.eventSubscribe('plugin:toolbox:page:show', function (options) {
        // render toolbox container with placeholders
        // bridge.render();
        // inject requested page
        // bridge.setPage(options);

        // Sandbox.eventNotify('global:content:render', {
        //     name: 'CommonBodyCenter',
        //     el: bridge.$el,
        //     keepExisted: true
        // });

        debugger;
        Sandbox.eventNotify('global:content:render', _.extend({}, options, {
            name: 'CommonBodyCenter',
        }));
    });

    Sandbox.eventSubscribe('plugin:toolbox:menu:display', function (options) {
        Sandbox.eventNotify('global:content:render', _.extend({}, options, {
            name: 'CommonBodyLeft',
        }));
        Sandbox.eventNotify('global:menu:set-active');
    });

    var Router = Backbone.Router.extend({
        routes: {
            "signin": "signin",
            "signout": "signout",
        },

        initialize: function () {
            // debugger;
        },

        signin: function () {
            // debugger;
            var self = this;
            require(['plugin/toolbox/toolbox/js/view/signin'], function (SignIn) {
                // using this wrapper to cleanup previous view and create new one
                Cache.withObject('SignIn', function (cachedView) {
                    // debugger;
                    // remove previous view
                    // if (cachedView && cachedView.remove)
                    //     cachedView.remove();

                    // create new view
                    var signin = cachedView || new SignIn();
                    signin.render();
                    Sandbox.eventNotify('global:content:render', {
                        name: 'CommonBodyCenter',
                        el: signin.el
                    });

                    // return view object to pass it into this function at next invocation
                    return signin;
                });
            });
        },

        signout: function () {
            Sandbox.eventNotify('plugin:toolbox:logout');
        }

    });

    return Router;
});