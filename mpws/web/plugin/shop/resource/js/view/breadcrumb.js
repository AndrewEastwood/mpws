APP.Modules.register("plugin/shop/view/breadcrumb", [], [
    'lib/jquery',
    'lib/underscore',
    'view/mview',
    'plugin/shop/model/breadcrumb',
], function (app, Sandbox, $, _, MView, modelBreadcrumb) {

    var Breadcrumb = MView.extend({

        name: "shopBreadcrumb",

        model: modelBreadcrumb,

        template: 'plugin.shop.component.shoppingCartEmbedded@hbs',

        initialize: function () {

            var self = this;

            Sandbox.eventSubscribe("shop:category:changed", function (data) {
                self.model.set('categoryId', data.categoryId);
            });

            Sandbox.eventSubscribe("shop:product:changed", function (data) {
                self.model.set('productId', data.productId);
            });

            this.listenTo(this.model, "change", this.render);

        }

    });

    return Breadcrumb;

});