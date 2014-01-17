(function(){

    var _globalConfig = JSON.parse(JSON.stringify(window.MPWS));

    if (!_globalConfig.ISDEV)
        delete window.MPWS;

    window.app = {
        config: _globalConfig
    };

    require.config({
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


    // start customer application
    require(['customer/js/' + (_globalConfig.ISTOOLBOX ? 'toolbox' : 'site')]);

})();