define("base/js/components/x-bootstrap-wysihtml5", [
    'jquery'
], function ($) {

    var require = APP.getRequireJS({
        shim: {
            'base/js/lib/inputs-ext/wysihtml5/wysihtml5': {
                deps: ['jquery', 'base/js/lib/inputs-ext/wysihtml5/bootstrap-wysihtml5-0.0.2/bootstrap3-wysihtml5'],
                exports: "wysihtml5"
            },
            // 'base/js/lib/inputs-ext/wysihtml5/bootstrap-wysihtml5-0.0.2/wysihtml5-0.3.0': {
            //     deps: ['jquery']
            // },
            // 'base/js/lib/inputs-ext/wysihtml5/bootstrap-wysihtml5-0.0.2/bootstrap-wysihtml5-0.0.2': {
            //     deps: ['base/js/lib/inputs-ext/wysihtml5/bootstrap-wysihtml5-0.0.2/wysihtml5-0.3.0']
            // },
            'base/js/lib/inputs-ext/wysihtml5/bootstrap-wysihtml5-0.0.2/bootstrap3-wysihtml5': {
                // deps: ['base/js/lib/inputs-ext/wysihtml5/bootstrap-wysihtml5-0.0.2/wysihtml5-0.3.0']
            }
            // 'base/js/lib/inputs-ext/wysihtml5/bootstrap3-wysihtml5': {
            //     exports: "wysihtml5"
            // }
        }
    });
    QbDfdMapbox = new $.Deferred();
    require([
        'base/js/lib/inputs-ext/wysihtml5/wysihtml5'
    ], function (wysihtml5) {
        if (wysihtml5) {
            QbDfdMapbox.resolve();
        } else {
            QbDfdMapbox.reject();
        }
    });

    return QbDfdMapbox;
});