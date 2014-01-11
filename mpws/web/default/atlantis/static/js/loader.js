(function(){

    var _globalConfig = JSON.parse(JSON.stringify(window.MPWS));

    if (!window.MPWS.ISDEV)
        delete window.MPWS;

    require.config({
        baseUrl: _globalConfig.PATH_STATIC_BASE,
        paths: {
            // default paths
            default: _globalConfig.URL_STATIC_DEFAULT,
            // def_lib: _globalConfig.URL_STATIC_DEFAULT + 'js/lib',
            // def_model: _globalConfig.URL_STATIC_DEFAULT + 'js/model',
            // def_view: _globalConfig.URL_STATIC_DEFAULT + 'js/view',
            // def_collection: _globalConfig.URL_STATIC_DEFAULT + 'js/collection',
            // def_plugin: _globalConfig.URL_STATIC_DEFAULT + 'js/plugin',
            // def_hbs: _globalConfig.URL_STATIC_DEFAULT + 'hbs',
            // def_nls: _globalConfig.URL_STATIC_DEFAULT + 'nls',
            // def_css: _globalConfig.URL_STATIC_DEFAULT + 'css',

            // customer paths
            customer: _globalConfig.URL_STATIC_CUSTOMER,
            // cust_lib: _globalConfig.URL_STATIC_CUSTOMER + 'js/lib',
            // cust_model: _globalConfig.URL_STATIC_CUSTOMER + 'js/model',
            // cust_view: _globalConfig.URL_STATIC_CUSTOMER + 'js/view',
            // cust_collection: _globalConfig.URL_STATIC_CUSTOMER + 'js/collection',
            // cust_plugin: _globalConfig.URL_STATIC_CUSTOMER + 'js/plugin',
            // cust_hbs: _globalConfig.URL_STATIC_CUSTOMER + 'hbs',
            // cust_nls: _globalConfig.URL_STATIC_CUSTOMER + 'nls',
            // cust_css: _globalConfig.URL_STATIC_CUSTOMER + 'css',

            // plugin paths
            plugin: _globalConfig.URL_STATIC_PLUGIN,

            // version suppress
            cmn_jquery: _globalConfig.URL_STATIC_DEFAULT + 'js/lib/jquery-1.9.1'
        },
        waitSeconds: 15,
        urlArgs: "v=" + (_globalConfig.ISDEV ? (new Date()).getTime() : _globalConfig.BUILD)
    });

    // start customer application
    require(['customer/js/app']);

})();