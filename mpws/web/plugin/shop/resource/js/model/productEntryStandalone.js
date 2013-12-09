APP.Modules.register("plugin/shop/model/productEntryStandalone", [], [
    'lib/underscore',
    'model/mmodel',
    'plugin/shop/lib/utils'
], function (app, Sandbox, _, MModel, shopUtils) {

    var productEntryStandalone = MModel.extend({

        _options: {
            realm: 'plugin',

            caller: 'shop',

            fn: 'shop_product_item_full',

            urldata: {
                productId: false
            }
        },

        initialize: function (options) {

            MModel.prototype.initialize.call(this, _.extend({}, this._options, options));
            app.log(true, 'model productEntryStandalone initialize', this);

        },

        parse: function (data) {
            app.log(true, 'model productEntryStandalone parse', data);

            // adjust product data
            if (data)
                data = shopUtils.adjustProductEntry(data);

            if (data[this.attributes.urldata.productId])
                data = data[this.attributes.urldata.productId];

            // // set error message
            // if (!data)
            //     data = "Product entry is empty";

            return data;
        }

    });

    return productEntryStandalone;

});