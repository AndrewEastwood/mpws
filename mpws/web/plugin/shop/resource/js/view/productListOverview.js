APP.Modules.register("plugin/shop/view/productListOverview", [], [
    'lib/jquery',
    'lib/underscore',
    'view/mview',
    'lib/mpws.page',
    'plugin/shop/model/productListOverview',
], function (app, Sandbox, $, _, MView, mpwsPage, modelProductListOverview) {

    var ProductListOverview = MView.extend({


        initialize: function (viewConfig) {

            var _viewConfig = _.extend({}, {
                name: "shopProductListOverview",
                model: new modelProductListOverview(),
                dependencies: {
                    productEntryViewList: {
                        url: "plugin.shop.component.productEntryViewList@hbs",
                        type: mpwsPage.TYPE.PARTIAL
                    }
                },
                template: 'plugin.shop.component.shopProductListOverview@hbs',
            }, viewConfig);

            // var self = this;

            app.log('init ProductListOverview', _viewConfig);

            MView.prototype.initialize.call(this, _viewConfig);

            // this.listenTo(this.model, "change", this.render);

        }

    });

    return ProductListOverview;

});