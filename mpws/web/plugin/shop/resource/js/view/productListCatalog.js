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
            },
            pageSidebar: {
                url: "plugin.shop.component.pageSidebar@hbs",
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
            var _orig = _(this.model.getUrlData()).clone();

            // app.log(true, 'view shopProductListCatalog: applying new filterOptions', filterOptions);
            // app.log(true, 'view shopProductListCatalog: oring data', _orig);

            this.model.setUrlData(_.extend({}, this.model.getUrlData(), filterOptions));

        }

    });

    return ProductListCatalog;

});