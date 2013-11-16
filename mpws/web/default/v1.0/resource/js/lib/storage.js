// it is the base page router

APP.Modules.register("lib/storage", [
    /* import globals */
    window

], [
  'lib/jquery',
  'lib/underscore'
    /* component implementation */
], function (window, app, Sandbox, jQuery, _) {

    var _cache = {};

    function Storage () {}

    Storage.add = function (key, value) {
        _cache[key] = value;
    }

    Storage.remove = function (key) {
        delete _cache[key];
    }

    Storage.reset = function (key) {
        _cache = {};
    }

    Storage.get = function (key) {
        return _cache[key];
    }

    Storage.getAll = function () {
        return _cache;
    }

    Storage.has = function (key) {
        return _(_cache).has(key);
    }

    return Storage;

});