define("base/js/components/bootstrap3-editable", [
    'jquery'
], function ($) {

    var require = APP.getRequireJS({
        shim: {
            '//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js': {}
        }
    });
    QbDfdEditable = new $.Deferred();
    require([
        '//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js'
    ], function () {
        if ($.fn.editable) {
            QbDfdEditable.resolve();
        } else {
            QbDfdEditable.reject();
        }
    });

    return QbDfdEditable;
});