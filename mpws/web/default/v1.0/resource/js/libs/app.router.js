// it is the base page router

APP.Modules.register("lib/app.router", [
    /* import globals */
    window

], [

    /* import dep packages */
    'lib/zepto',
    'lib/underscore',
    'lib/backbone',
    // 'lib/jquery_ui',

    /* component implementation */
], function(wnd, app, Sandbox, $, _, Backbone) {

    // var _config = app.Page.getConfiguration();

    // predefined basic deps
    // for single page/site
    
    var BasePageRouter = function () {
        this.initialize.apply(this, arguments);
    };

    _.extend(BasePageRouter.prototype, {
        modulePageMap: [],
        useUrlQuery: false,
        name: "Base",
        initialize: function (config) {
            if (config && config.modulePageMap)
                this.modulePageMap = config.modulePageMap;
        },
        getPageModuels: function () {
            var activePage = app.Page.getUrl(this.useUrlQuery);
            
            app.log(true, this.name + ' active page is ', activePage);

            var modulesToDownload = [];
            var entry = null;
            var _stopped = false;

            for (var id in this.modulePageMap) {
                entry = this.modulePageMap[id];
                for (var ptrId in entry.match)
                    if (activePage.match(entry.match[ptrId])) {
                        for (var i = 0, len = entry.deps.length; i < len; i++)
                            if (modulesToDownload.indexOf(entry.deps[i]) < 0)
                                modulesToDownload.push(entry.deps[i]);
                        if (entry.stop)
                            _stopped = true;
                    }
                if (_stopped)
                    break;
            }

            return modulesToDownload;
        },

        start: function () {

            app.log(true, this.name + ' app page router started');

            // here we get page modules
            var pageModules = this.getPageModuels();

            app.log(true, this.name + ' loading: ', pageModules);
            var self = this;

            // include page dependencies
            APP.Modules.downloadPackages(pageModules, function () {
                // start all other routers
                var args = [].slice.apply(arguments);
                app.log(true, self.name + ' all packages are downloaded: ', args);

                var _runningRouters = 0;
                var _usedRouters = 0;
                var _onAllRoutersCompleteFn = function () {
                    // notify all subscribers that page is ready
                    $(document).ready(function () {
                        Sandbox.eventNotify("page-loaded");
                        app.log(true, 'app page is loaded');
                    });
                }

                _(args).each(function(argObj) {
                    if (argObj && argObj.isRouter) {
                        var _router = new argObj();
                        _router.start();
                        _usedRouters++;
                        _runningRouters++;
                        _router.onPageLoaded(null, function () {
                            _runningRouters--;
                            if (_runningRouters === 0)
                                _onAllRoutersCompleteFn();
                        })
                    }
                });

                if (_usedRouters === 0)
                    _onAllRoutersCompleteFn();

            });

        },

        onPageLoaded: function (ctx, callback) {
            Sandbox.eventSubscribe("page-loaded", function () {
                if (typeof callback === "function")
                    callback.apply(ctx);
            });
        },

    });

    BasePageRouter.extend = Backbone.Router.extend;
    BasePageRouter.isRouter = true;

    return BasePageRouter;

});