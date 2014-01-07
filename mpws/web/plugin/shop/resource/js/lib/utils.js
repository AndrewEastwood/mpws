APP.Modules.register("plugin/shop/lib/utils", [], [
    'lib/jquery',
    'lib/underscore',
], function (app, Sandbox, $, _) {

    function Utils () {};

    Utils.adjustProductEntry = function (data) {
        var _products = {};

        if (!data.products)
            return _products;

        // map all by product id
        for (var pid in data.products) {
            // add product into collection
            _products[pid] = data.products[pid];
            // get product attributes
            var _attr = data.attributes && data.attributes[pid] ? _(data.attributes[pid]).clone() : {};
            // setup images
            var _images = {
                HAS_MAIN: false,
                HAS_ADDITIONAL: false,
                MAIN: false,
                ADDITIONAL : false
            }
            // adjust product images
            if (_attr.IMAGE) {
                if (_.isString(_attr.IMAGE)) {
                    _images.HAS_MAIN = true;
                    _images.MAIN = _attr.IMAGE;
                }
                if (_.isArray(_attr.IMAGE)) {
                    _images.HAS_MAIN = true;
                    _images.MAIN = _attr.IMAGE.shift();
                    if (_attr.IMAGE.length) {
                        _images.HAS_ADDITIONAL = true;
                        _images.ADDITIONAL = _attr.IMAGE;
                    }
                }
            } else {
                _images.MAIN = app.Page.getConfiguration().URL.staticUrlCustomer + 'noimage.png';
            }

            _attr.IMAGES = _images;

            _products[pid]['ProductAttributes'] = _attr;
        }

        // append price data
        if (data.prices)
            for (var pid in data.prices)
                _products[pid]['ProductPrices'] = data.prices[pid] || {};

        return _products;
    }

    return Utils;

});