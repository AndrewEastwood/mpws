define([
    "underscore",
    'jquery.cookie'
], function (_) {

    var _cache = {};

    function Cache () {}

    Cache.setObject = function (name, object) {
        _cache[name] = object;
    }

    Cache.getObject = function (name) {
        return _cache[name];
    }

    Cache.deleteObject = function (name) {
        delete _cache[name];
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

    Cache.deleteCookie = function (key) {
        Cache.setCookie(key, null, {expires: -1});
    }

    Cache.saveInLocalStorage = function (key, data) {
        localStorage.setItem(key, JSON.stringify(data));
    }

    Cache.getFromLocalStorage = function (key) {
        return JSON.parse(localStorage.getItem(key));
    }
    Cache.removeFromLocalStorage = function (key) {
        localStorage.removeItem(key);
    }

    Cache.set = function (key, data) {
        var _wrapper = {d: data};
        if (localStorage && localStorage.setItem)
            Cache.saveInLocalStorage(key, _wrapper);
        else if ($.cookie)
            Cache.setCookie(key, _wrapper);
        else
            Cache.setObject(key, _wrapper);
    }

    Cache.get = function (key) {
        var _wrapper = null;
        if (localStorage && localStorage.getItem)
            _wrapper = Cache.getFromLocalStorage(key);
        else if ($.cookie)
            _wrapper = Cache.getCookie(key);
        else
            _wrapper = Cache.getObject(key);
        if (_wrapper && _wrapper.d)
            return _wrapper.d;
    }

    Cache.getOnce = function (key) {
        var _val = Cache.get(key);
        if (localStorage && localStorage.removeItem)
            Cache.removeFromLocalStorage(key);
        else if ($.cookie)
            Cache.deleteCookie(key);
        else
            Cache.deleteObject(key);
        return _val;
    }

    return Cache;

});