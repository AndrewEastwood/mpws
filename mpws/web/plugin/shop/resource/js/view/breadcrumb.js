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

            // extend parent
            MView.prototype.initialize.call(this, options);

            Sandbox.eventSubscribe("shop:category:changed", function (data) {
                self.model.set('categoryId', data.categoryId);
            });

            Sandbox.eventSubscribe("shop:product:changed", function (data) {
                self.model.set('productId', data.productId);
            });

        }

    });

    return Breadcrumb;

});