APP.Modules.register("plugin/shop/view/productListCatalog", [], [
    'lib/jquery',
    'lib/underscore',
    'view/mview',
    'lib/mpws.page',
    'plugin/shop/model/productListCatalog',
], function (app, Sandbox, $, _, MView, mpwsPage, modelProductListCatalog) {

    var ProductListCatalog = MView.extend({

        name: "shopProductListCatalog",

        model: new modelProductListCatalog(),

        template: 'plugin.shop.component.shopProductListCatalog@hbs',

        dependencies: {
            productEntryViewList: {
                url: "plugin.shop.component.productEntryViewList@hbs",
                type: mpwsPage.TYPE.PARTIAL
            }
        },

        initialize: function (options) {

            // extend parent
            MView.prototype.initialize.call(this, options);

            // app.log('view ProductListCatalog initialize', this);
        },

        applyFiltering: function (filterOptions) {

            // prepare filter params
            // filterOptions

            // and then update url data to
            // get new list of products rendered
            // this.model.setUrlData();

        }

    });

    return ProductListCatalog;

});