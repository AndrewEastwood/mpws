APP.Modules.register("plugin/shop/model/productListCatalog", [], [
    'lib/underscore',
    'model/mmodel',
    'plugin/shop/lib/utils'
], function (app, Sandbox, _, MModel, shopUtils) {

    var ProductListCatalog = MModel.extend({

        _options: {
            realm: 'plugin',

            caller: 'shop',

            fn: 'shop_catalog',

            urldata: {
                categoryId: null,

                // common

                filter_priceMax: null,

                filter_priceMin: 0,

                filter_availability: {},

                filter_onSaleTypes: {},

                // category based (use specifications of current category)

                filter_brandIds: [],

                filter_specifications: {}
            }
        },

        initialize: function (options) {

            MModel.prototype.initialize.call(this, _.extend({}, this._options, options));
            app.log('model ProductListCatalog initialize', this);

        },

        parse: function (data) {
            app.log('model ProductListCatalog parse', data);

            data = shopUtils.adjustProductEntry(data);

            return data;
        }

    });

    return ProductListCatalog;

});