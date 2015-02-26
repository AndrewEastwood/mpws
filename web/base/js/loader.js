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
        'string': 'base/js/utils/extend.string',
        'file-upload': 'base/js/utils/file-upload',
        'image-upload': 'base/js/utils/image-upload',
        'formatter-price': 'base/js/utils/formatter-price',
        'handlebars-helpers': 'base/js/utils/handlebars-helpers',
        'sandbox': 'base/js/utils/sandbox',
        'utils': 'base/js/utils/utils',
        // vendors
        'backbone': 'base/js/vendors/backbone/backbone',
        'backbone-pageable': 'base/js/vendors/backbone-pageable/lib/backbone-pageable',
        'backgrid': 'base/js/vendors/backgrid/lib/backgrid',
        'backgrid-filter': 'base/js/vendors/backgrid-filter/backgrid-filter',
        'backgrid-htmlcell': 'base/js/vendors/backgrid-htmlcell/backgrid-htmlcell',
        'backgrid-paginator': 'base/js/vendors/backgrid-paginator/backgrid-paginator',
        'backgrid-select-all': 'base/js/vendors/backgrid-select-all/backgrid-select-all',
        'backgrid-select2-cell': 'base/js/vendors/backgrid-select2-cell/backgrid-select2-cell',
        'bootstrap': 'base/js/vendors/bootstrap/dist/js/bootstrap',
        'bootstrap-select': 'base/js/vendors/bootstrap-select/dist/js/bootstrap-select',
        'bootstrap-switch': 'base/js/vendors/bootstrap-switch/dist/js/bootstrap-switch',
        'bootstrap-tagsinput': 'base/js/vendors/bootstrap-tagsinput/dist/js/bootstrap-tagsinput',
        'bootstrap-dialog': 'base/js/vendors/bootstrap3-dialog/dist/js/bootstrap-dialog',
        'cachejs': 'base/js/vendors/cachejs/cache',
        'handlebars': 'base/js/vendors/handlebars/handlebars',
        'jquery': 'base/js/vendors/jquery/jquery',
        'jquery.bridget': 'base/js/vendors/jquery-bridget/jquery.bridget',
        'jquery.browser': 'base/js/vendors/jquery.browser/dist/jquery.browser',
        'jquery.cookie': 'base/js/vendors/jquery.cookie/jquery.cookie',
        'jquery.maskedinput': 'base/js/vendors/jquery.maskedinput/dist/jquery.maskedinput',
        'jquery.steps': 'base/js/vendors/jquery.steps/build/jquery.steps',
        'jstree': 'base/js/vendors/jstree/dist/jstree',
        'jsurl': 'base/js/vendors/jsurl/url',
        'lightbox': 'base/js/vendors/lightbox/js/lightbox',
        'moment': 'base/js/vendors/moment/moment',
        'requirejs': 'base/js/vendors/requirejs/require',
        'bootstrap-slider': 'base/js/vendors/seiyria-bootstrap-slider/js/bootstrap-slider',
        'select2': 'base/js/vendors/select2/js/select2',
        'bootstrap-datetimepicker': 'base/js/vendors/smalot-bootstrap-datetimepicker/js/bootstrap-datetimepicker',
        'spin': 'base/js/vendors/spin.js/spin',
        'spinjs': 'base/js/vendors/spin.js/jquery.spin',
        'typehead': 'base/js/vendors/typehead.js/dist/typehead.jquery',
        'underscore': 'base/js/lib/underscore',
        'x-editable': 'base/js/vendors/x-editable/dist/bootstrap3-editable/js/bootstrap-editable',
        // libs
        'i18nprecompile': 'base/js/lib/i18nprecompile',
        'json2': 'base/js/lib/json2',
        'bootstrap-magnify': 'base/js/lib/bootstrap-magnify',
        // plugins
        'hbs': 'base/js/plugin/hbs',
        'i18n': 'base/js/plugin/i18n',
        'css': 'base/js/plugin/css'
    },
    shim: {
        'bootstrap': {
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
        },
        'select2': {
            deps: ['jquery']
        },
        'jquery.bridget': {
            deps: ['jquery']
        },
        'jquery.steps': {
            deps: ['jquery']
        },
        'jquery.maskedinput': {
            deps: ['jquery']
        },
        'jsurl': {
            exports: 'Url',
            deps: ['jquery']
        },
        'lightbox': {
            deps: ['jquery']
        },
        'typehead': {
            deps: ['jquery']
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