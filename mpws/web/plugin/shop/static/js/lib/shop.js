APP.Modules.register("plugin/shop/lib/shop", [], [
    'lib/jquery',
    'lib/mpws.api',
    'lib/utils',
    'lib/underscore',
], function (app, Sandbox, $, mpwsAPI, Utils, _) {

    var _logPrefix = '[plugin/shop/lib/driver] : ';

    // app.log('HI FROM SHOP DRIVER :) I AM SHOP DRIVER LIBRARY YO')

    function _dataInterfaceFn (data, type) {
        return {
            type: type || "none",
            data: data || {}
        }
    }

    function _adjustProductEntry (data) {
        var _products = {};

        if (!data.products)
            return _products;

        // map all by product id
        for (var pid in data.products) {
            // add product into collection
            _products[pid] = data.products[pid];
            // get product attributes
            var _attr = data.attributes && data.attributes[pid] || {};
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

    function pluginShopDriver () {}

    pluginShopDriver.prototype.getProductItemByID = function (params, callback) {
        app.log(_logPrefix, 'getProductItemByID', mpwsAPI/*, arguments.callee.caller*/);
        mpwsAPI.requestData({
            caller: 'shop',
            fn: 'shop_product_item_full',
            params: _.extend(params, {
                realm: 'plugin'
            })
        }, function (error, data) {
            if (data)
                data = JSON.parse(data);

            // adjust product data
            data = _adjustProductEntry(data);

            // get product entry
            data = data[params.productId];

            // set error message
            if (!data)
                error = "Product entry is empty";

            if (typeof callback === "function")
                callback.call(null, error, _dataInterfaceFn(data));
        })
    }

    pluginShopDriver.prototype.getProductsByCategory = function (params, callback) {
        app.log(_logPrefix, 'getProductsByCategory', mpwsAPI/*, arguments.callee.caller*/);
        mpwsAPI.requestData({
            caller: 'shop',
            fn: 'shop_products_category',
            params: _.extend(params, {
                realm: 'plugin'
            })
        }, function (error, data) {
            if (data)
                data = JSON.parse(data);

            // adjust product data
            data = _adjustProductEntry(data);
            // app.log(data);

            if (typeof callback === "function")
                callback.call(null, error, _dataInterfaceFn(data));
        })
    }

    pluginShopDriver.prototype.getProductListLatest = function (callback) {
        app.log(_logPrefix, 'getProductListLatest', mpwsAPI/*, arguments.callee.caller.caller*/);
        mpwsAPI.requestData({
            caller: 'shop',
            fn: 'shop_product_list_latest',
            params: {
                realm: 'plugin',
                limit: 16
            }
        }, function (error, data) {
            if (data)
                data = JSON.parse(data);

            // adjust product data
            data = _adjustProductEntry(data);
            // app.log(data);

            if (typeof callback === "function")
                callback.call(null, error, _dataInterfaceFn(data));
        })
    }

    pluginShopDriver.prototype.getProductAttributes = function (productId, callback) {
        app.log(_logPrefix, 'getProductAttributes', mpwsAPI)
        mpwsAPI.requestData({
            caller: 'shop',
            fn: 'shop_product_attributes',
            params: {
                realm: 'plugin',
                // productId: _.isArray(productId) ? productId.join(',') : productId
                productId: productId
            }
        }, function (error, data) {
            if (data)
                data = JSON.parse(data);
            if (typeof callback === "function")
                callback.call(null, error, _dataInterfaceFn(data));
        })
    }

    pluginShopDriver.prototype.getProductPriceArchive = function (productId, callback) {
        app.log(_logPrefix, 'getProductAttributes', mpwsAPI)
        mpwsAPI.requestData({
            caller: 'shop',
            fn: 'shop_product_price_archive',
            params: {
                realm: 'plugin',
                productId: productId
            }
        }, function (error, data) {
            if (data)
                data = JSON.parse(data);
            if (typeof callback === "function")
                callback.call(null, error, _dataInterfaceFn(data));
        })
    }

    pluginShopDriver.prototype.getShopCatalogStructure = function (callback) {
        app.log(_logPrefix, 'getShopCatalogStructure', mpwsAPI)
        mpwsAPI.requestData({
            caller: 'shop',
            fn: 'shop_catalog_structure',
            params: {
                realm: 'plugin'
            }
        }, function (error, data) {
            if (data)
                data = JSON.parse(data);
            if (typeof callback === "function") {
                // app.log(true, 'Utils.getTreeByJson', data);
                data = Utils.getTreeByJson(data, 'ID', 'ParentID');
                callback.call(null, error, _dataInterfaceFn(data));
            }
        })
    }

    // shopping cart
    pluginShopDriver.prototype.getShoppingCart = function (callback) {
        app.log(_logPrefix, 'getShoppingCart'/*, arguments.callee.caller*/);
        mpwsAPI.requestData({
            caller: 'shop',
            fn: 'shop_cart_content',
            params: {
                realm: 'plugin'
            }
        }, function (error, data) {
            if (data)
                data = JSON.parse(data);

            // adjust shopping cart products
            data.products = {};
            var _tempProductEntry = false;
            _(data.items).each(function (productEntry, productID){
                if (!productEntry)
                    return;
                // get product entry
                _tempProductEntry = _adjustProductEntry(productEntry);
                // update collection
                if (_tempProductEntry[productID])
                    data.products[productID] = _tempProductEntry[productID];
            });

            // cleanup
            delete data.items;

            if (typeof callback === "function")
                callback.call(null, error, _dataInterfaceFn(data));
        })
    }

    pluginShopDriver.prototype.shoppingCartManager = function (params, callback) {
        app.log(_logPrefix, 'shoppingCartManager', params)
        mpwsAPI.requestData({
            caller: 'shop',
            fn: 'shop_cart_manage',
            params: $.extend(params, {
                realm: 'plugin'
            })
        }, function (error, data) {
            if (data)
                data = JSON.parse(data);

            // adjust shopping cart products
            data.products = {};
            var _tempProductEntry = false;
            _(data.items).each(function (productEntry, productID){
                if (!productEntry)
                    return;
                // get product entry
                _tempProductEntry = _adjustProductEntry(productEntry);
                // update collection
                if (_tempProductEntry[productID])
                    data.products[productID] = _tempProductEntry[productID];
            });

            // cleanup
            delete data.items;

            if (typeof callback === "function") {
                callback.call(null, error, _dataInterfaceFn(data));
            }
        });
    }

    pluginShopDriver.prototype.shoppingCartAdd = function (productId, callback) {
        this.shoppingCartManager({
            productId: productId,
            amount: 1
        }, callback);
    }

    pluginShopDriver.prototype.shoppingCartRemove = function (productId, callback) {
        this.shoppingCartManager({
            productId: productId,
            amount: 0
        }, callback);
    }

    pluginShopDriver.prototype.shoppingCartClear = function (callback) {
        this.shoppingCartManager({
            clear: true
        }, callback);
    }

    pluginShopDriver.prototype.shoppingCartPreview = function (data, callback) {
        this.shoppingCartManager({
            clear: true
        }, callback);
    }

    pluginShopDriver.prototype.shoppingCartSave = function (data, callback) {
        this.shoppingCartManager({
            clear: true
        }, callback);
    }

    // shop location (breadcrumb)
    pluginShopDriver.prototype.getShopLocation = function (params, callback) {
        app.log(_logPrefix, 'getShopLocation', mpwsAPI);
        mpwsAPI.requestData({
            caller: 'shop',
            fn: 'shop_location',
            params: $.extend(params, {
                realm: 'plugin'
            })
        }, function (error, data) {
            if (data)
                data = JSON.parse(data);
            if (typeof callback === "function") {
                callback.call(null, error, _dataInterfaceFn(data));
            }
        });
    }

    pluginShopDriver.prototype.getShopCategoryFilteringData = function (params, callback) {
        // TODO:
        // get:
        // 1. subcategories
        // 2. origins
        // 3. product count
        // 4. page count (based on items per page "IPP")
        // 5. price stats: [min ... max] (need for price filtering)
        // 6. top rated proucts for current category
        // 7. top boughts
        // 8. on sale
        app.log(_logPrefix, 'getShopCategoryFilteringData', mpwsAPI);
        mpwsAPI.requestData({
            caller: 'shop',
            fn: 'shop_category_filtering',
            params: $.extend(params, {
                realm: 'plugin'
            })
        }, function (error, data) {
            if (data)
                data = JSON.parse(data);
            if (typeof callback === "function") {
                callback.call(null, error, _dataInterfaceFn(data));
            }
        });
    }

    return pluginShopDriver;


});
