APP.Modules.register("plugin/shop/view/productListOverview", [], [
    'lib/jquery',
    'lib/underscore',
    'view/mview',
    'lib/mpws.page',
    'plugin/shop/model/productListOverview',
    /* ui components */
    'lib/bootstrap'
], function (app, Sandbox, $, _, MView, mpwsPage, modelProductListOverview) {

    var ProductListOverview = MView.extend({

        name: "shopProductListOverview",

        model: new modelProductListOverview(),

        template: 'plugin.shop.component.shopProductListOverview@hbs',

        dependencies: {
            productEntryViewList: {
                url: "plugin.shop.component.productEntryViewList@hbs",
                type: mpwsPage.TYPE.PARTIAL
            }
        },

        initialize: function (options) {

            // extend parent
            MView.prototype.initialize.call(this, options);

            // app.log('view ProductListOverview initialize', this);
        }

    });

    return ProductListOverview;

});