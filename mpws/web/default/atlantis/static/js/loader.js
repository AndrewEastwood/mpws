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
        hbs: {
            i18nDirectory: _globalConfig.URL_STATIC_DEFAULT + 'nls/',
            i18n: true
        },
        waitSeconds: 15,
        urlArgs: "v=" + (_globalConfig.ISDEV ? (new Date()).getTime() : _globalConfig.BUILD)
    });

    var _filesToRequest = ['customer/js/' + (_globalConfig.ISTOOLBOX ? 'toolbox' : 'site')];

    for (var key in _globalConfig.PLUGINS)
        _filesToRequest.push('plugin/' + _globalConfig.PLUGINS[key] + '/js/site');
        // debugger;
    // start customer application
    require(_filesToRequest, function () {
        var _args = [].slice.call(arguments);
        var _customerJs = _args[0];

        // setup plugin routers
        var pluginCount  = _args.length;
        if (pluginCount > 1)
            for (var i = 1; i < pluginCount; i++) {
                // debugger;
                /*var router = */new _args[i](_customerJs);
                // router.name = _globalConfig.PLUGINS[i - 1];
            }

        // window.app.customer = _customerJs;

        // start/init customer
        _customerJs.start();

        Backbone.history.start();  // Запускаем HTML5 History push
    });

})();