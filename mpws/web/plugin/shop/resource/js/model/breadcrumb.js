APP.Modules.register("plugin/shop/model/breadcrumb", [], [
    'lib/jquery',
    'lib/underscore',
    'model/mmodel',
    'lib/mpws.api',
    'lib/mpws.page',
    'plugin/shop/lib/shop'
], function (app, Sandbox, $, _, MModel, mpwsAPI, mpwsPage, pluginShopModel) {
    

    var Breadcrumb = MModel.extend({

        realm: 'plugin',

        caller: 'shop',

        fn: 'shop_location',

        categoryId: false,

        productId: false,

        // urlData: {
        //     caller: 'shop',
        //     fn: 'shop_location',
        //     params: $.extend(params, {
        //         realm: 'plugin'
        //     })
        // }

        events: {
            "change:categoryId": "fetch",
            "change:productId": "fetch"
        }

        // fetch: function () {
        //     app.log(_logPrefix, 'getShopLocation', mpwsAPI);
        //     mpwsAPI.requestData({
        //         caller: 'shop',
        //         fn: 'shop_location',
        //         params: $.extend(params, {
        //             realm: 'plugin'
        //         })
        //     }, function (error, data) {
        //         if (data)
        //             data = JSON.parse(data);
        //         if (typeof callback === "function") {
        //             callback.call(null, error, _dataInterfaceFn(data));
        //         }
        //     });
        // },

        parse: function (data) {
        }

    });

    return Breadcrumb;

});