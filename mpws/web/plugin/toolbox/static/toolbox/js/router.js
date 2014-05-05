define("plugin/toolbox/toolbox/js/router", [
    'default/js/lib/sandbox',
    'cmn_jquery',
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'default/js/lib/cache',
    'plugin/toolbox/toolbox/js/view/menu',
    'plugin/toolbox/toolbox/js/model/auth',
    'default/js/lib/jquery.tinysort'
], function (Sandbox, $, _, Backbone, Cache, Menu) {

    var renderCompleteSent = false;

    // get user status when page starts
    Sandbox.eventSubscribe('global:loader:complete', function (status) {
        // debugger;
        Sandbox.eventNotify('plugin:toolbox:status');
    });

    Sandbox.eventSubscribe('global:session:expired', function (error) {
        if (error === "InvalidPublicTokenKey") {
            document.location.reload();
            throw "Session timeout";
        }

        // debugger;
        if (_(["LoginRequired", "AccessDenied"]).indexOf(error) >= 0) {
            if (Backbone.history.fragment !== "signin")
                Sandbox.eventNotify('plugin:toolbox:logout');
            return;
        }

        throw error;
    });

    // get user status with each page route action
    Sandbox.eventSubscribe('global:route', function (hash) {
        if (renderCompleteSent)
            Sandbox.eventNotify('plugin:toolbox:status');
    });

    // handle user status
    Sandbox.eventSubscribe('plugin:toolbox:status:received', function (status) {
        // debugger;
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
            if (!renderCompleteSent) {
                renderCompleteSent = true;
                Sandbox.eventNotify('plugin:toolbox:render:complete');
            }
        } else {
            Backbone.history.navigate('signin', true);
        }
    });

    // when user is signed in do smth
    Sandbox.eventSubscribe('plugin:toolbox:signed:in', function (status) {
        APP.commonElements.empty();
        var _location = localStorage.location || '';
        Backbone.history.navigate(_location, true);
        if (!renderCompleteSent) {
            renderCompleteSent = true;
            Sandbox.eventNotify('plugin:toolbox:render:complete');
        }
    });

    // redirect user to the signin page
    Sandbox.eventSubscribe('plugin:toolbox:signed:out', function (status) {
        Backbone.history.navigate('signin', true);
        renderCompleteSent = false;
    });

    // inject into toolbox layout another plugin's content
    // this is a bridge between layout an other plugins
    // it is better to do render through these events
    Sandbox.eventSubscribe('plugin:toolbox:page:show', function (options) {
        Sandbox.eventNotify('global:content:render', _.extend({}, options, {
            name: 'CommonBodyCenter',
        }));
    });

    Sandbox.eventSubscribe('plugin:toolbox:menu:display', function (options) {
        Sandbox.eventNotify('global:content:render', _.extend({}, options, {
            name: 'CommonBodyLeft',
        }));
        // sort nodes
        // debugger;
        // $('#toolbox-menu-ID').tsort({place:'top'});
        Sandbox.eventNotify('global:menu:set-active');
    });

    var Router = Backbone.Router.extend({
        routes: {
            "signin": "signin",
            "signout": "signout",
        },

        signin: function () {
            // debugger;
            var self = this;
            require(['plugin/toolbox/toolbox/js/view/signin'], function (SignIn) {
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
        },

        signout: function () {
            Sandbox.eventNotify('plugin:toolbox:logout');
        }

    });

    return Router;
});