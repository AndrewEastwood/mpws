APP.Modules.register("plugin/shop/lib/driver", [], [
    'lib/jquery',
    'lib/mpws.api'
], function (app, Sandbox, $, mpwsAPI) {

    var _logPrefix = '[plugin/shop/lib/driver] : ';

    // app.log('HI FROM SHOP DRIVER :) I AM SHOP DRIVER LIBRARY YO')

    function pluginShopDriver () {}

    pluginShopDriver.prototype.getProductItemByID = function (productId, callback) {
        app.log(_logPrefix, 'getProductItemByID', mpwsAPI)
        mpwsAPI.requestData({
            caller: 'shop',
            fn: 'product_item_full',
            params: {
                realm: 'plugin',
                pid: productId
            }
        }, function (error, data) {
            if (data)
                data = JSON.parse(data);
            if (typeof callback === "function")
                callback.call(null, error, data);
        })
    }

    pluginShopDriver.prototype.getProductListLatest = function (callback) {
        app.log(_logPrefix, 'getProductListLatest', mpwsAPI)
        mpwsAPI.requestData({
            caller: 'shop',
            fn: 'product_list_latest',
            params: {
                realm: 'plugin',
                limit: 16
            }
        }, function (error, data) {
            if (data)
                data = JSON.parse(data);
            if (typeof callback === "function")
                callback.call(null, error, data);
        })
    }

    pluginShopDriver.prototype.getProductAttributes = function (productId, callback) {
        app.log(_logPrefix, 'getProductAttributes', mpwsAPI)
        mpwsAPI.requestData({
            caller: 'shop',
            fn: 'product_attributes',
            params: {
                realm: 'plugin',
                pid: productId
            }
        }, function (error, data) {
            if (data)
                data = JSON.parse(data);
            if (typeof callback === "function")
                callback.call(null, error, data);
        })
    }

    pluginShopDriver.prototype.getProductPriceArchive = function (productId, callback) {
        app.log(_logPrefix, 'getProductAttributes', mpwsAPI)
        mpwsAPI.requestData({
            caller: 'shop',
            fn: 'product_price_archive',
            params: {
                realm: 'plugin',
                pid: productId
            }
        }, function (error, data) {
            if (data)
                data = JSON.parse(data);
            if (typeof callback === "function")
                callback.call(null, error, data);
        })
    }

    return pluginShopDriver;


});
