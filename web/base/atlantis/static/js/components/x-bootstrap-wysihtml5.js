define("default/js/components/x-bootstrap-wysihtml5", [
    'cmn_jquery'
], function ($) {

    var require = APP.getRequireJS({
        shim: {
            'default/js/lib/inputs-ext/wysihtml5/wysihtml5': {
                deps: ['default/js/lib/inputs-ext/wysihtml5/bootstrap-wysihtml5-0.0.2/bootstrap3-wysihtml5']
            },
            // 'default/js/lib/inputs-ext/wysihtml5/bootstrap-wysihtml5-0.0.2/wysihtml5-0.3.0': {
            //     deps: ['cmn_jquery']
            // },
            // 'default/js/lib/inputs-ext/wysihtml5/bootstrap-wysihtml5-0.0.2/bootstrap-wysihtml5-0.0.2': {
            //     deps: ['default/js/lib/inputs-ext/wysihtml5/bootstrap-wysihtml5-0.0.2/wysihtml5-0.3.0']
            // },
            'default/js/lib/inputs-ext/wysihtml5/bootstrap-wysihtml5-0.0.2/bootstrap3-wysihtml5': {
                // deps: ['default/js/lib/inputs-ext/wysihtml5/bootstrap-wysihtml5-0.0.2/wysihtml5-0.3.0']
            }
            // 'default/js/lib/inputs-ext/wysihtml5/bootstrap3-wysihtml5': {
            //     exports: "wysihtml5"
            // }
        }
    });
    QbDfdMapbox = new $.Deferred();
    require([
        'default/js/lib/inputs-ext/wysihtml5/wysihtml5'
    ], function () {
        if ($.fn.wysihtml5) {
            QbDfdMapbox.resolve();
        } else {
            QbDfdMapbox.reject();
        }
    });

    return QbDfdMapbox;
});