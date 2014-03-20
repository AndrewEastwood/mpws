define("plugin/toolbox/js/toolbox", [
    'default/js/lib/sandbox',
    'customer/js/site',
    'cmn_jquery',
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'default/js/lib/cache',
    'plugin/shop/js/view/toolbox/menu'
], function (Sandbox, Site, $, _, Backbone, Cache) {

    Sandbox.eventSubscribe('route', function (hash) {
        debugger;
    });

    Sandbox.eventSubscribe('global:loader:complete', function (hash) {
        debugger;
    });

    var Router = Backbone.Router.extend({
        routes: {
            "signin": "signin",
            "signout": "signout",
        },

        initialize: function () {
            var self = this;

            // Sandbox.eventSubscribe('toolbox:page:login', function () {
            //     self.login();
            // });

            Sandbox.eventSubscribe('toolbox:page:index', function () {
                Sandbox.eventNotify('toolbox:breadcrumb:show');
            });

            Sandbox.eventSubscribe('toolbox:page:show', function (page) {
                // Sandbox.eventNotify('toolbox:breadcrumb:show');
                self.showToolboxPage(page);
            });
        },

        dashboard: function () {
            
        },

        signin: function () {

        },

        signout: function () {

        },

        showToolboxPage: function (pageContent) {
            require(['plugin/toolbox/js/view/toolbox/pageHolder'], function (PageHolder) {
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
                    Sandbox.eventNotify('toolbox:content:render', {
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