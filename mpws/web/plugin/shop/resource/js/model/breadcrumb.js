APP.Modules.register("plugin/shop/model/breadcrumb", [], [
    'lib/jquery',
    'lib/underscore',
    'model/mmodel',
    'lib/mpws.api',
    'lib/mpws.page',
    'plugin/shop/lib/shop'
], function (app, Sandbox, $, _, MModel, mpwsAPI, mpwsPage, pluginShopModel) {
    

    var Breadcrumb = MModel.extend({

        _options: {
            realm: 'plugin',

            caller: 'shop',

            fn: 'shop_location',

            categoryId: false,

            productId: false,
        },


//         // urlData: {
//         //     caller: 'shop',
//         //     fn: 'shop_location',
//         //     params: $.extend(params, {
//         //         realm: 'plugin'
//         //     })
//         // }

        events: {
            "change:categoryId": "fetch",
            "change:productId": "fetch"
        },
// ,

        initialize: function (options) {

            MModel.prototype.initialize.call(this, _.extend({}, this._options, options));
            app.log('model Breadcrumb initialize', this);

        },

    });

    return Breadcrumb;

});