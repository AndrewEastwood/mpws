'use strict';
require.config({
    locale: 'ua_uk',
    baseUrl: (function () {
        var config = this && this.MPWS || {};
        return config.PATH_STATIC_BASE || '/static/';
    })(),
    paths: {
        // utils
        'auth': 'base/js/utils/auth',
        'bootstrap-alert': 'base/js/utils/bootstrap-alert',
        'cache': 'base/js/utils/cache',
        'string': 'base/js/utils/extend.string',
        'file-upload': 'base/js/utils/file-upload',
        'image-upload': 'base/js/utils/image-upload',
        'formatter-price': 'base/js/utils/formatter-price',
        'handlebars-helpers': 'base/js/utils/handlebars-helpers',
        'sandbox': 'base/js/utils/sandbox',
        'utils': 'base/js/utils/utils',
        // libs
        'handlebars': 'base/js/lib/handlebars',
        'jquery': 'base/js/lib/jquery-1.9.1',
        'underscore': 'base/js/lib/underscore',
        'backbone': 'base/js/lib/backbone',
        'moment': 'base/js/lib/moment/moment',
        'bootstrap': 'base/js/lib/bootstrap',
        'backgrid': 'base/js/lib/backgrid',
        'i18nprecompile': 'base/js/lib/i18nprecompile',
        'json2': 'base/js/lib/json2',
        'jquery.cookie': 'base/js/lib/jquery.cookie',
        'bootstrap-select': 'base/js/lib/bootstrap-select'
        'bootstrap-tagsinput': 'base/js/lib/bootstrap-tagsinput'
        'bootstrap-magnify': 'base/js/lib/bootstrap-magnify'
        // plugins
        'hbs': 'base/js/plugin/hbs',
        'i18n': 'base/js/plugin/i18n',
        'css': 'base/js/plugin/css'
    },
    shim: {
        bootstrap: {
            deps: ['jquery']
        },
        'bootstrap-select': {
            deps: ['jquery', 'bootstrap']
        },
        'bootstrap-tagsinput': {
            deps: ['jquery', 'bootstrap']
        },
        'bootstrap-magnify': {
            deps: ['jquery', 'bootstrap']
        }
    },
    waitSeconds: 30,
    deps: ['base/js/app'],
    urlArgs: "mpws_bust=" + (function () {
        var config = this && this.MPWS || {},
            bust = new Date().getTime();
        return config.DEBUG && config.BUILD || bust;
    })()
});