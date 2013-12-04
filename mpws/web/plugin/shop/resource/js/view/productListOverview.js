APP.Modules.register("plugin/shop/view/productListOverview", [], [
    'lib/jquery',
    'lib/underscore',
    'view/mview',
    'lib/mpws.page',
    'plugin/shop/model/productListOverview',
], function (app, Sandbox, $, _, MView, mpwsPage, modelProductListOverview) {

    var ProductListOverview = MView.extend({

        model: new modelProductListOverview(),
        
        template: 'plugin.shop.component.shopProductListOverview@hbs',
        // _options: {

        name: "shopProductListOverview",

        dependencies: {
            productEntryViewList: {
                url: "plugin.shop.component.productEntryViewList@hbs",
                type: mpwsPage.TYPE.PARTIAL
            }
        },

        // },

        initialize: function (options) {

            // var _viewConfig = _.extend({}, {
            // }, viewConfig);

            // var self = this;

            // app.log('init ProductListOverview', _viewConfig);
            // MView.prototype.initialize.call(this, _.extend({}, this._options, options));
            MView.prototype.initialize.call(this, options);
            app.log('view ProductListOverview initialize', this);

            // this.listenTo(this.model, "change", this.render);

        }

    });

    return ProductListOverview;

});