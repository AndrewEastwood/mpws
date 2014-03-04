(function(){

    var _globalConfig = JSON.parse(JSON.stringify(window.MPWS));

    if (!_globalConfig.ISDEV)
        delete window.MPWS;

    window.app = {
        config: _globalConfig
    };

    require.config({
        locale: _globalConfig.LOCALE,
        baseUrl: _globalConfig.PATH_STATIC_BASE,
        // mpws: _globalConfig,
        paths: {
            // default paths
            default: _globalConfig.URL_STATIC_DEFAULT,
            // customer paths
            customer: _globalConfig.URL_STATIC_CUSTOMER,
            // plugin paths
            plugin: _globalConfig.URL_STATIC_PLUGIN,
            // version suppress
            cmn_jquery: _globalConfig.URL_STATIC_DEFAULT + 'js/lib/jquery-1.9.1'
        },
        waitSeconds: 15,
        urlArgs: "v=" + (_globalConfig.ISDEV ? (new Date()).getTime() : _globalConfig.BUILD)
    });

    // include site file
    var _filesToRequest = ['customer/js/site'];

    for (var key in _globalConfig.PLUGINS)
        _filesToRequest.push('plugin/' + _globalConfig.PLUGINS[key] + '/js/' + (_globalConfig.ISTOOLBOX ? 'toolbox' : 'site'));
        // debugger;
    // start customer application
    console.log(_filesToRequest);
    require(_filesToRequest, function () {
        var _args = [].slice.call(arguments);
        var _customerJs = _args[0];
        var _routers = [];

        // setup plugin routers
        var pluginCount  = _args.length;
        if (pluginCount > 1)
            for (var i = 1; i < pluginCount; i++) {
                // debugger;
                var router = new _args[i](_customerJs);
                _routers.push(router);
                // router.name = _globalConfig.PLUGINS[i - 1];
            }

        // window.app.customer = _customerJs;

        // start/init customer
        _customerJs.start();
        // append undefined action to the last router
        // debugger;
        // _routers[_routers.length - 1].route("*nomatch", "*nomatch", function() {
        //     console.log('mpws: 404');
        //     Backbone.history.navigate("", {trigger: true});
        // });
        // var _paths = {};
        // for (var rk in _routers) {
        //     // debugger;
        //     for (var pk in _routers[rk].routes)
        //         _paths[pk] = _routers[rk].routes[pk];
        // }

        // _paths["*nomatch"] = 'notFound';

        // '*nomatch': 'notFound'
        // notFound: function () {
        //     console.log('shop: 404');
        //     Backbone.history.navigate("", {trigger: true});
        // },

        // debugger;



        // new Backbone.Router.extend({
        //     routes: {
        //         '*nomatch': 'notFound'
        //     },
        //     notFound: function () { 
        //         Backbone.history.navigate("", {trigger: true});
        //     }
        // });

        Backbone.history.start();  // Запускаем HTML5 History push
    });

})();