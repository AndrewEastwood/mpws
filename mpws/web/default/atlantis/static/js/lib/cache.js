define("default/js/lib/cache", [
    "default/js/lib/underscore",
    "default/js/lib/jquery.cookie"
], function (_) {

    var _cache = {};

    function Cache () {}

    Cache.setObject = function (name, object) {
        _cache[name] = object;
    }

    Cache.getObject = function (name) {
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

    Cache.set = function (key, data) {
        if (localStorage && localStorage.setItem)
            Cache.saveInLocalStorage(key, data);
        else if ($.cookie)
            Cache.setCookie(key, data);
        else
            Cache.setObject(key, data);
    }

    Cache.get = function (key) {
        if (localStorage && localStorage.setItem)
            return Cache.getFromLocalStorage(key);
        else if ($.cookie)
            return Cache.getCookie(key);
        else
            return Cache.getObject(key);
    }

    return Cache;

});
