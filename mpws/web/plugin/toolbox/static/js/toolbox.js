define("plugin/toolbox/js/toolbox", [
    'default/js/lib/sandbox',
    'customer/js/site',
    'cmn_jquery',
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'default/js/lib/cache',
    // 'plugin/toolbox/js/view/bridge'
], function (Sandbox, Site, $, _, Backbone, Cache) {

    // var bridge = new ViewBridge();

    var Router = Backbone.Router.extend({
        routes: {
            "signin": "signin",
            "signout": "signout",
        },

        initialize: function () {
            var self = this;

            Sandbox.eventSubscribe('plugin:toolbox:status:received', function () {
                // debugger;
                // Sandbox.eventNotify('global:breadcrumb:show');
            });
            // Sandbox.eventSubscribe('toolbox:page:login', function () {
            //     self.login();
            // });
            Sandbox.eventSubscribe('global:route', function (hash) {
                // debugger;
            });

            Sandbox.eventSubscribe('global:loader:complete', function (hash) {
                // debugger;
            });

            Sandbox.eventSubscribe('global:page:index', function () {
                // debugger;
                // Sandbox.eventNotify('global:breadcrumb:show');
            });

            Sandbox.eventSubscribe('plugin:toolbox:page:show', function (page) {
                // debugger;
                // Sandbox.eventNotify('global:breadcrumb:show');
                self.showToolboxPage(page);
            });


            // Sandbox.eventSubscribe('global:page:show', function (page) {
            //     debugger;
            //     // Sandbox.eventNotify('toolbox:breadcrumb:show');
            //     self.showToolboxPage(page);
            // });
        },

        // dashboard: function () {
            
        // },

        signin: function () {
            var self = this;
            require(['plugin/toolbox/js/view/toolbox/signin'], function (SignIn) {
                // using this wrapper to cleanup previous view and create new one
                Cache.withObject('SignIn', function (cachedView) {
                    // debugger;
                    // remove previous view
                    if (cachedView && cachedView.remove)
                        cachedView.remove();

                    // create new view
                    var signin = new SignIn();
                    // Site.placeholders.account.pageProfile.html(pageHolder.el);
                    signin.on('mview:renderComplete', function () {
                        signin.setPagePlaceholder(pageContent);
                    });
                    signin.fetchAndRender({
                        action: 'status'
                    });
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
            var self = this;
            require(['plugin/toolbox/js/view/toolbox/signout'], function (SignOut) {
                // using this wrapper to cleanup previous view and create new one
                Cache.withObject('SignOut', function (cachedView) {
                    // debugger;
                    // remove previous view
                    if (cachedView && cachedView.remove)
                        cachedView.remove();

                    // create new view
                    var signout = new SignOut();
                    // Site.placeholders.account.pageProfile.html(signout.el);
                    signout.on('mview:renderComplete', function () {
                        signout.setPagePlaceholder(pageContent);
                    });
                    signout.fetchAndRender({
                        action: 'status'
                    });
                    Sandbox.eventNotify('global:content:render', {
                        name: 'SignOut',
                        el: signout.el
                    });

                    // return view object to pass it into this function at next invocation
                    return signout;
                });
            });
        },

        showToolboxPage: function (pageContent) {
            require(['plugin/toolbox/js/view/toolbox/bridge'], function (PageHolder) {
                // using this wrapper to cleanup previous view and create new one
                Cache.withObject('PageHolder', function (cachedView) {
                    // debugger;
                    // remove previous view
                    if (cachedView && cachedView.remove)
                        cachedView.remove();

                    // create new view
                    var pageHolder = new PageHolder();
                    // Site.placeholders.account.pageProfile.html(pageHolder.el);
                    pageHolder.on('mview:renderComplete', function () {
                        pageHolder.setPagePlaceholder(pageContent);
                    });
                    pageHolder.fetchAndRender({
                        action: 'status'
                    });
                    Sandbox.eventNotify('global:content:render', {
                        name: 'PageHolder',
                        el: pageHolder.el
                    });

                    // return view object to pass it into this function at next invocation
                    return pageHolder;
                });
            });
        }

    });

    return Router;
});