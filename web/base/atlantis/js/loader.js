'use strict';
require.config({
    locale: (function () {
        var config = MPWS || {};
        return config.LOCALE;
    })(),
    baseUrl: (function () {
        var config = MPWS || {};
        return config.PATH_STATIC_BASE;
    })(),
    paths: {
        // default paths
        'default': (function () {
                var config = MPWS || {};
                return config.URL_STATIC_DEFAULT;
            })(),
        // website (origin customer)
        'website': (function () {
                var config = MPWS || {};
                return config.URL_STATIC_WEBSITE;
            })(),
        // customer paths (current running customer)
        'customer': (function () {
                var config = MPWS || {};
                return config.URL_STATIC_CUSTOMER;
            })(),
        // plugin paths
        'plugin': (function () {
                var config = MPWS || {};
                return config.URL_STATIC_PLUGIN;
            })(),
        // version suppress
        'jquery': (function () {
                var config = MPWS || {};
                return config.URL_STATIC_DEFAULT + '/js/lib/jquery-1.9.1';
            })()
    },
    waitSeconds: 30,
    deps: ['default/js/app'],
    urlArgs: "mpws_bust=" + (function () {
        var config = MPWS || {},
            bust = new Date().getTime();
        return config.DEBUG && config.BUILD || bust;
    })()
});