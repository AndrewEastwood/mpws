define("default/js/lib/cache", function (){

    var _cache = {};

    function Cache () {}

    Cache.getObject = function (name, setupFn) {
        if (_cache[name])
            return _cache[name];
        else {
            _cache[name] = setupFn();
            return _cache[name];
        }
    }

    return Cache;

});
