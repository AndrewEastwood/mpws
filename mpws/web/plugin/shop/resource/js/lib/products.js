APP.Modules.register("plugin/shop/lib/products", [], [
    'lib/jquery',
    'lib/mpws.api'
], function (app, Sandbox, $, mpwsAPI) {

    var _logPrefix = '[plugin/shop/lib/products] : ';

    app.log('HI FROM SHOP PLUGIN :) I AM PRODUCTS LIBRARY YO')

    function pluginShopProducts () {


        this.getProductByID = function (productId, callback) {

            app.log(_logPrefix, 'getProductByID', mpwsAPI)
            mpwsAPI.request({
                caller: 'shop',
                fn: 'product_single_full',
                params: {
                    realm: 'plugin',
                    pid: productId
                }
            }, function (data) {
                if (typeof callback === "function")
                    callback(data)
            })

        }

    }


    return pluginShopProducts;


});