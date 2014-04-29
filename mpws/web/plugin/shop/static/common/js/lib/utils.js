define("plugin/shop/common/js/lib/utils", [
    'cmn_jquery',
    'default/js/lib/underscore',
], function ($, _) {

    function Utils () {};

    Utils.adjustProductItems = function (products) {
        if (!products)
            return false;
        _(products).each(function(product) {
            Utils.adjustProductItem(product);
        });

        return products;
    }

    Utils.adjustProductItem = function (product) {


        // debugger;

        // map all by product id
        // var _tmpProduct = null;

        // _(data.products).each(function(product) {

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
            // if (product.Prices && product.Prices[pid])
            //     product['ProductPrices'] = product.prices[pid];
        // });

        return product;
    }

    return Utils;

});