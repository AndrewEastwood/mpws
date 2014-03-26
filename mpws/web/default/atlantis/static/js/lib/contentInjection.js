define("default/js/lib/contentInjection", [
    'cmn_jquery'
], function ($) {

    var _injectContent = function (cnt, options) {

        if (!options || !options.name)
            return;

        if (!cnt || !cnt.length)
            return;

        // debugger;
        if (options.keepExisted) {
            // get element id that we want to replace
            var _elID = options.el.attr('id');
            // find existed elements
            var _items = cnt.find('#' + _elID);
            // do nothing when element exists
            if (_items.length > 0)
                return;
        }

        // debugger;
        if (options.replace) {
            // get element id that we want to replace
            var _elID = options.el.attr('id');
            if (_elID) {
                // find existed elements
                var _items = cnt.find('#' + _elID);
                // if there are some we do replace
                if (_items.length > 0)
                    _items.replaceWith(options.el);
                else // or just append as new one into container
                    cnt.append(options.el);
            }
            return;
        }

        if (options.append)
            cnt.append(options.el);
        else if (options.prepend)
            cnt.prepend(options.el);
        else
            cnt.html(options.el);
    }

    return {
        injectContent: _injectContent
    }

});