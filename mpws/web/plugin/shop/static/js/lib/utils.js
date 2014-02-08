define("plugin/shop/js/lib/utils", [
    'cmn_jquery',
    'default/js/lib/underscore',
], function ($, _) {

    function Utils () {};

    Utils.adjustProductEntry = function (data) {
        var _products = [];

        if (!data.products)
            return _products;

        // map all by product id
        var _tmpProduct = null;

        _(data.products).each(function(product) {

            // debugger;
            var pid = product.ID;

            product.Attributes = product.Attributes || {};
            // _tmpProduct = 
            // // add product into collection
            // _products[pid] = data.products[pid];
            // get product attributes
            var _attr = product.Attributes;
            // setup images
            var _images = {
                HAS_MAIN: false,
                HAS_ADDITIONAL: false,
                MAIN: false,
                ADDITIONAL : false,
                EMPTY: app.config.URL_STATIC_DEFAULT + 'img/noimage.png'
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
                _images.MAIN = _images.EMPTY;
            }

            _attr.IMAGES = _images;

            // product['ProductAttributes'] = _attr;

            // append price data
            if (data.prices && data.prices[pid])
                product['ProductPrices'] = data.prices[pid];
        });

        return data.products;
    }

    return Utils;

});