define("default/js/lib/bootstrap-alert", [
    'cmn_jquery',
    'default/js/lib/underscore'
], function ($, _) {

    function Alerts () {}

    var _msgContainer = $('<div/>').attr('class', 'bootstrap-alert');

    var _msgStack = [];

    Alerts.success = function (message) {
        return _getMessageBox('success', message);
    }

    Alerts.info = function (message) {
        return _getMessageBox('info', message);
    }

    Alerts.warning = function (message) {
        return _getMessageBox('warning', message);
    }

    Alerts.danger = function (message) {
        return _getMessageBox('danger', message);
    }

    function _validateSetup () {
        if (!_msgContainer.is(":visible"))
            $('body').append(_msgContainer);
    }

    function _getMessageBox (type, message) {
        _validateSetup();

        var _msg = $('<div/>').attr('class', 'alert alert-popup alert-' + type).html(message);

        _msgContainer.prepend(_msg);
        _msgStack.unshift(_msg);

        _msg.on('click', _closeAlert);

        setTimeout(function () {
            _closeAlert.call(_msg);
        }, 3000);

        if (_msgStack.length > 3)
            _closeAlert(_msgStack.pop());

        _msg.close = function () {
            _closeAlert.call(_msg);
        }

        return _msg;
    }

    function _closeAlert (itemToRremove) {
        if (itemToRremove) {
            $(itemToRremove).remove();
            return;
        }

        $(this).remove();
        _msgStack.splice(_(_msgStack).indexOf($(this)), 1);
    }

    return Alerts;

});