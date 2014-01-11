APP.Modules.register("lib/appCachedViews", [], [

], function (app, Sandbox){

    var _cache = {};

    var appCachedViews = function () {}

    appCachedViews.getView = function (pathToView, setupFn, serveFn) {

        if (_cache[pathToView])
            return serveFn(_cache[pathToView]);

        app.Modules.require(pathToView, function (viewObj) {
            _cache[pathToView] = setupFn(viewObj);
            serveFn(_cache[pathToView]);
        });
    }

    return appCachedViews;

});
