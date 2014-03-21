define("default/js/lib/cache", function (){

    var _cache = sessionStorage || {};

    function Cache () {}

    Cache.getObject = function (name, setupFn) {
        if (_cache[name])
            return _cache[name];
        else {
            if (typeof setupFn === "function")
                Cache.setObject(setupFn());
            return _cache[name];
        }
    }

    Cache.setObject = function (name, object) {
        _cache[name] = object;
    }

    Cache.hasObject = function (name) {
        return !!_cache[name];
    }

    Cache.withObject = function (name, handler) {
        if (typeof handler === "function")
            _cache[name] = handler(_cache[name]);
        else
            return _cache[name];
    }

    return Cache;

});
