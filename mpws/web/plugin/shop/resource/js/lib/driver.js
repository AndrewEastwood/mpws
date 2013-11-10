APP.Modules.register("plugin/shop/lib/driver", [], [
    'lib/jquery',
    'lib/mpws.api',
    'lib/utils'
], function (app, Sandbox, $, mpwsAPI, Utils) {

    var _logPrefix = '[plugin/shop/lib/driver] : ';

    // app.log('HI FROM SHOP DRIVER :) I AM SHOP DRIVER LIBRARY YO')

    function _dataInterfaceFn (data, type) {
        return {
            type: type || "none",
            data: data || {}
        }
    }

    function pluginShopDriver () {}

    pluginShopDriver.prototype.getProductItemByID = function (productId, callback) {
        app.log(_logPrefix, 'getProductItemByID', mpwsAPI/*, arguments.callee.caller*/);
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
                callback.call(null, error, _dataInterfaceFn(data));
        })
    }

    pluginShopDriver.prototype.getProductListLatest = function (callback) {
        app.log(_logPrefix, 'getProductListLatest', mpwsAPI/*, arguments.callee.caller.caller*/);
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

            // map all by product id
            if (data.attributes && data.products) {
                for (var pid in data.products)
                    data.products[pid]['ProductAttributes'] = data.attributes[pid] || {};
            }

            // app.log(data);

            if (typeof callback === "function")
                callback.call(null, error, _dataInterfaceFn(data));
        })
    }

    pluginShopDriver.prototype.getProductAttributes = function (productId, callback) {
        app.log(_logPrefix, 'getProductAttributes', mpwsAPI)
        mpwsAPI.requestData({
            caller: 'shop',
            fn: 'product_attributes',
            params: {
                realm: 'plugin',
                // pid: _.isArray(productId) ? productId.join(',') : productId
                pid: productId
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
            fn: 'product_price_archive',
            params: {
                realm: 'plugin',
                pid: productId
            }
        }, function (error, data) {
            if (data)
                data = JSON.parse(data);
            if (typeof callback === "function")
                callback.call(null, error, _dataInterfaceFn(data));
        })
    }

    pluginShopDriver.prototype.getShoppingCart = function (callback) {
        app.log(_logPrefix, 'getProductItemByID', mpwsAPI/*, arguments.callee.caller*/);
        mpwsAPI.requestData({
            caller: 'shop',
            fn: 'shop_cart_content',
            params: {
                realm: 'plugin'
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

    pluginShopDriver.prototype.shoppingCartManager = function (params, callback) {
        app.log(_logPrefix, 'shopBuy', mpwsAPI)
        mpwsAPI.requestData({
            caller: 'shop',
            fn: 'shop_cart_manage',
            params: $.extend(params, {
                realm: 'plugin'
            })
        }, function (error, data) {
            if (data)
                data = JSON.parse(data);
            if (typeof callback === "function") {
                callback.call(null, error, _dataInterfaceFn(data));
            }
        })
    }

    pluginShopDriver.prototype.shoppingCartAdd = function (productId, callback) {
        this.shoppingCartManager({
            pid: productId,
            amount: 1
        }, callback);
    }

    pluginShopDriver.prototype.shoppingCartRemove = function (productId, callback) {
        this.shoppingCartManager({
            pid: productId,
            amount: 0
        }, callback);
    }

    pluginShopDriver.prototype.shoppingCartClear = function (callback) {
        this.shoppingCartManager({
            clear: true
        }, callback);
    }

    return pluginShopDriver;


});
