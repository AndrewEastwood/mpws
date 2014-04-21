define(function () {

    var _globalConfig = JSON.parse(JSON.stringify(window.MPWS));

    if (!_globalConfig.ISDEV)
        delete window.MPWS;

    window.app = {
        config: _globalConfig
    };

    // set requirejs configuration
    require.config({
        locale: _globalConfig.LOCALE,
        baseUrl: _globalConfig.PATH_STATIC_BASE,
        // mpws: _globalConfig,
        paths: {
            // application
            application: _globalConfig.URL_STATIC_DEFAULT + "js/app",
            // default paths
            default: _globalConfig.URL_STATIC_DEFAULT,
            // website (origin customer)
            website: _globalConfig.URL_STATIC_WEBSITE,
            // customer paths (current running customer)
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
    var _filesToRequest = ['default/js/lib/sandbox', _globalConfig.ROUTER];

    for (var key in _globalConfig.PLUGINS)
        _filesToRequest.push('plugin/' + _globalConfig.PLUGINS[key] + '/' + (_globalConfig.ISTOOLBOX ? 'toolbox' : 'site') + '/js/router');

    // console.log(_filesToRequest);
    // start customer application
    require(_filesToRequest, function (Sandbox, Site) {
        var _args = [].slice.call(arguments, 1);
        var _routers = [];

        // setup plugin routers
        var pluginCount  = _args.length;
        if (pluginCount > 1)
            for (var i = 1; i < pluginCount; i++) {
                // debugger;
                var router = new _args[i](Site);
                _routers.push(router);
            }

        // notify all that loader completed its tasks
        Sandbox.eventNotify('global:loader:complete');

        // start HTML5 History push
        Backbone.history.start();
    });

})
