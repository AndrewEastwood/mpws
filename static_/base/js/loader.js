'use strict';
require.config({
    locale: 'ua_uk',
    baseUrl: '/static_/',
    paths: {
        // utils
        'auth': 'base/js/utils/auth',
        'bootstrap-alert': 'base/js/utils/bootstrap-alert',
        'string': 'base/js/utils/extend.string',
        'file-upload': 'base/js/utils/file-upload',
        'image-upload': 'base/js/utils/image-upload',
        'formatter-price': 'base/js/utils/formatter-price',
        'handlebars-helpers': 'base/js/utils/handlebars-helpers',
        'handlebars-partials': 'base/js/utils/handlebars-partials',
        'sandbox': 'base/js/utils/sandbox',
        'utils': 'base/js/utils/utils',
        'createPopupTitle': 'base/js/utils/createPopupTitle',
        // vendors
        'backbone': 'vendors/backbone/backbone',
        'backbone-pageable': 'vendors/backbone-pageable/lib/backbone-pageable',
        'backgrid': 'vendors/backgrid/lib/backgrid',
        'backgrid-filter': 'vendors/backgrid-filter/backgrid-filter',
        'backgrid-htmlcell': 'vendors/backgrid-htmlcell/backgrid-htmlcell',
        'backgrid-paginator': 'vendors/backgrid-paginator/backgrid-paginator',
        'backgrid-select-all': 'vendors/backgrid-select-all/backgrid-select-all',
        'backgrid-select2-cell': 'vendors/backgrid-select2-cell/backgrid-select2-cell',
        'bootstrap': 'vendors/bootstrap/dist/js/bootstrap',
        'bootstrap-select': 'vendors/bootstrap-select/dist/js/bootstrap-select',
        'bootstrap-switch': 'vendors/bootstrap-switch/dist/js/bootstrap-switch',
        'bootstrap-tagsinput': 'vendors/bootstrap-tagsinput/dist/bootstrap-tagsinput',
        'bootstrap-dialog': 'vendors/bootstrap3-dialog/dist/js/bootstrap-dialog',
        'bootstrap-slider': 'vendors/seiyria-bootstrap-slider/js/bootstrap-slider',
        'bootstrap-datetimepicker': 'vendors/smalot-bootstrap-datetimepicker/js/bootstrap-datetimepicker',
        'bootstrap-editable': 'vendors/x-editable/dist/bootstrap3-editable/js/bootstrap-editable',
        'bootstrap-magnify': 'vendors/bootstrap-magnify/js/bootstrap-magnify',
        'bootstrap-wysiwyg': 'vendors/bootstrap-wysiwyg/bootstrap-wysiwyg',
        'cachejs': 'vendors/cachejs/cache',
        'handlebars': 'vendors/handlebars/handlebars',
        'jquery': 'vendors/jquery/jquery',
        'jquery.bridget': 'vendors/jquery-bridget/jquery.bridget',
        'jquery.browser': 'vendors/jquery.browser/dist/jquery.browser',
        'jquery.cookie': 'vendors/jquery.cookie/jquery.cookie',
        'jquery.maskedinput': 'vendors/jquery.maskedinput/dist/jquery.maskedinput',
        'jquery.maskmoney': 'vendors/jquery-maskmoney/dist/jquery.maskMoney',
        'jquery.steps': 'vendors/jquery.steps/build/jquery.steps',
        'jstree': 'vendors/jstree/dist/jstree',
        'jsurl': 'vendors/jsurl/url',
        'lightbox': 'vendors/lightbox/js/lightbox',
        'moment': 'vendors/moment/moment',
        'requirejs': 'vendors/requirejs/require',
        'select2': 'vendors/select2/select2',
        'spinjs': 'vendors/spin.js/jquery.spin',
        'spin': 'vendors/spin.js/spin',
        'typehead': 'vendors/typehead.js/dist/typehead.jquery',
        'underscore': 'vendors/underscore/underscore',
        'echo': 'vendors/echojs/dist/echo',
        'asyncjs': 'vendors/async/lib/async',
        'owl.carousel': 'vendors/owl.carousel/dist/owl.carousel',
        'odometer': 'vendors/odometer/odometer',
        'icheck': 'vendors/iCheck/icheck',
        'routefilter': 'vendors/routefilter/dist/backbone.routefilter',
        // libs
        'i18nprecompile': 'base/js/lib/i18nprecompile',
        'json2': 'base/js/lib/json2',
        // plugins
        'async': 'base/js/plugin/async',
        'css': 'base/js/plugin/css',
        'goog': 'base/js/plugin/goog',
        'hbs': 'base/js/plugin/hbs',
        'i18n': 'base/js/plugin/i18n',
        'image': 'base/js/plugin/image',
        'propertyParser': 'base/js/plugin/propertyParser',
        'text': 'base/js/plugin/text',
        // jquery-file-upload
        'jquery.fileupload': 'vendors/blueimp-file-upload/js/jquery.fileupload',
        'jquery.fileupload-ui': 'vendors/blueimp-file-upload/js/jquery.fileupload-ui',
        'jquery.fileupload-image': 'vendors/blueimp-file-upload/js/jquery.fileupload-image',
        'jquery.fileupload-validate': 'vendors/blueimp-file-upload/js/jquery.fileupload-validate',
        'jquery.fileupload-video': 'vendors/blueimp-file-upload/js/jquery.fileupload-video',
        'jquery.fileupload-audio': 'vendors/blueimp-file-upload/js/jquery.fileupload-audio',
        'jquery.fileupload-process': 'vendors/blueimp-file-upload/js/jquery.fileupload-process',
        'jquery.ui.widget': 'vendors/blueimp-file-upload/js/vendor/jquery.ui.widget',
        'jquery.iframe-transport': 'vendors/blueimp-file-upload/js/jquery.iframe-transport',
        'load-image': 'vendors/blueimp-load-image/js/load-image',
        'load-image-meta': 'vendors/blueimp-load-image/js/load-image-meta',
        'load-image-exif': 'vendors/blueimp-load-image/js/load-image-exif',
        'load-image-ios': 'vendors/blueimp-load-image/js/load-image-ios',
        'canvas-to-blob': 'vendors/blueimp-canvas-to-blob/js/canvas-to-blob',
        'tmpl': 'vendors/blueimp-tmpl/js/tmpl'
    },
    shim: {
        'backgrid': {
            exports: 'Backgrid',
            deps: ['underscore', 'backbone']
        },
        'backgrid-filter': {
            deps: ['backbone', 'backgrid']
        },
        'backgrid-htmlcell': {
            deps: ['backgrid']
        },
        'backgrid-paginator': {
            deps: ['backgrid', 'underscore', 'backbone-pageable', 'backbone']
        },
        'backgrid-select-all': {
            deps: ['backgrid', 'backbone']
        },
        'backgrid-select2-cell': {
            deps: ['backgrid', 'backbone', 'select2']
        },
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
        'bootstrap-wysiwyg': {
            deps: ['jquery', 'bootstrap']
        },
        'select2': {
            deps: ['jquery']
        },
        'vendors/select2/select2_locale_uk': {
            deps: ['jquery', 'select2']
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
        'jquery.maskmoney': {
            deps: ['jquery']
        },
        'jquery.fileupload': {
            deps: [
                'jquery',
                'load-image',
                'canvas-to-blob',
                'jquery.iframe-transport'
            ]
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
        },
        'owl.carousel': {
            deps: ['jquery']
        },
        'icheck': {
            deps: ['jquery']
        }
    },
    waitSeconds: 30,
    deps: ['base/js/app'],
    config: {
        'base/js/app': {
            'urlStatic': '/static_/'
        }
    },
    urlArgs: "bust=" + (function () {
        return this && this.MPWS && this.MPWS.BUILD || Date.now();
    })()
});