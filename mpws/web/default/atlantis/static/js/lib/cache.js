define("default/js/lib/cache", [
    "default/js/lib/underscore",
    "default/js/lib/jquery.cookie"
], function (_) {

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

    Cache.hasObject = function (name) {
        return !!_cache[name];
    }

    Cache.withObject = function (name, handler) {
        if (typeof handler === "function")
            _cache[name] = handler(_cache[name]);
        else
            return _cache[name];
    }

    Cache.setCookie = function (key, jsonData, options) {
        $.cookie.json = true;
        $.cookie(key, jsonData, options);
        $.cookie.json = false;
    }

    Cache.getCookie = function (key) {
        $.cookie.json = true;
        var val = $.cookie(key);
        $.cookie.json = false;
        return val;
    }

    Cache.saveInLocalStorage = function (key, data) {
        localStorage.setItem(key, JSON.stringify(data));
    }

    Cache.getFromLocalStorage = function (key) {
        return JSON.parse(localStorage.getItem(key));
    }

    return Cache;

});
