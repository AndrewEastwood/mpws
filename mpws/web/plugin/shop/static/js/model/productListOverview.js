APP.Modules.register("plugin/shop/model/productListOverview", [], [
    'lib/underscore',
    'model/mmodel',
    'plugin/shop/lib/utils'
], function (app, Sandbox, _, MModel, shopUtils) {

    var ProductListOverview = MModel.extend({

        _options: {
            realm: 'plugin',

            caller: 'shop',

            fn: 'shop_product_list_latest',
        },

        initialize: function (options) {

            MModel.prototype.initialize.call(this, _.extend({}, this._options, options));
            app.log('model ProductListOverview initialize', this);

        },

        parse: function (data) {
            app.log('model ProductListOverview parse', data);

            data = shopUtils.adjustProductEntry(data);

            return data;
        }

    });

    return ProductListOverview;

});