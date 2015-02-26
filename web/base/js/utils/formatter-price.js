define([
    'underscore',
    'base/js/lib/sprintf'
], function (_, sprintf) {

    return function (amount, currency, display) {
        // using formatting from sprintf: https://github.com/jakobwesthoff/sprintf.js
        var curr = currency || null,
            displayFmt = _(display).findWhere({CurrencyName: currency}) || null,
            value = parseFloat(amount, 10).toFixed(2);
        if (displayFmt && displayFmt.Format) {
            value = sprintf(displayFmt.Format, value);
        }
        return value;
    }

});