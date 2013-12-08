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

            urldata: {
                categoryId: null,

                productId: null,
            }
        },

        initialize: function (options) {
            MModel.prototype.initialize.call(this, _.extend({}, this._options, options));
            app.log(true, 'model Breadcrumb model initialize', this);
        },

    });

    return Breadcrumb;

});