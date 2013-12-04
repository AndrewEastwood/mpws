APP.Modules.register("plugin/shop/model/productListOverview", [], [
    'lib/jquery',
    'lib/underscore',
    'model/mmodel',
    'lib/mpws.api',
    'lib/mpws.page',
    'plugin/shop/lib/shop'
], function (app, Sandbox, $, _, MModel) {
    

    var ProductListOverview = MModel.extend({

        realm: 'plugin',

        caller: 'shop',

        fn: 'shop_product_list_latest',

        parse: function (data) {
        }

    });

    return ProductListOverview;

});