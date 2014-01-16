define("plugin/shop/js/view/productListOverview", [
    'cmn_jquery',
    'default/js/lib/underscore',
    'default/js/view/mview',
    'default/js/lib/mpws.page',
    'plugin/shop/js/model/productListOverview',
    /* ui components */
    'default/js/lib/bootstrap'
], function ($, _, MView, mpwsPage, modelProductListOverview) {

    var ProductListOverview = MView.extend({

        name: "shopProductListOverview",

        model: new modelProductListOverview(),

        template: 'plugin/shop/hbs/component/shopProductListOverview.hbs',

        dependencies: [
            "plugin/shop/hbs/component/productEntryViewList.hbs"
        ],

        initialize: function (options) {

            // extend parent
            MView.prototype.initialize.call(this, options);

            // app.log('view ProductListOverview initialize', this);
        }

    });

    return ProductListOverview;

});