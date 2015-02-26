define("plugin/shop/common/js/lib/utils", [
    'jquery',
    'default/js/lib/underscore'
], function ($, _) {

    function Utils () {};

    Utils.priceFormatter = function (value, currency, display) {
        // using formatting from sprintf: https://github.com/jakobwesthoff/sprintf.js
        var curr = currency || null,
            displayFmt = _(display).findWhere({CurrencyName: currency}) || null,
            value = parseFloat(amount, 10).toFixed(2);
        if (displayFmt && displayFmt.Format) {
            value = sprintf(displayFmt.Format, value);
        }
        return value;
    }

    return Utils;

});