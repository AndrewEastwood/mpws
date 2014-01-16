define("default/js/lib/appCachedRequests", function (){

    var _cache = {};

    var appCachedRequests = function () {}

    appCachedRequests.getView = function (pathToView, setupFn, serveFn) {

        if (_cache[pathToView])
            return serveFn(_cache[pathToView]);

        require(pathToView, function (viewObj) {
            _cache[pathToView] = setupFn(viewObj);
            serveFn(_cache[pathToView]);
        });
    }

    return appCachedRequests;

});
