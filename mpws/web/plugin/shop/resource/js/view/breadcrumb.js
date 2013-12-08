APP.Modules.register("plugin/shop/view/breadcrumb", [], [
    'lib/jquery',
    'lib/underscore',
    'view/mview',
    'plugin/shop/model/breadcrumb',
], function (app, Sandbox, $, _, MView, modelBreadcrumb) {

    var Breadcrumb = MView.extend({

        name: "shopBreadcrumb",

        model: new modelBreadcrumb(),

        template: 'plugin.shop.component.breadcrumb@hbs',

        initialize: function (options) {

            var self = this;

            Sandbox.eventSubscribe("shop:category:changed", function (data) {
                app.log(true, 'shop:category:changed', data);
                self.model.setUrlData('categoryId', data.categoryId);
            });

            Sandbox.eventSubscribe("shop:product:changed", function (data) {
                app.log(true, 'shop:product:changed', data);
                // app.log(true, 'plugin/shop/view/breadcrumb', 'finding path to product', data);
                self.model.setUrlData('productId', data.productId);
            });

            // extend parent
            MView.prototype.initialize.call(this, options);

        }

    });

    return Breadcrumb;

});