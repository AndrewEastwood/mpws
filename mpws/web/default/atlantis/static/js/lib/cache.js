define("default/js/lib/cache", function (){

    var _cache = {};

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

    Cache.withObject = function (name, handler) {
        if (typeof handler === "function")
            handler(_cache[name]);
        return _cache[name];
    }

    return Cache;

});