APP.Modules.register("plugin/shop/view/breadcrumb", [], [
    'lib/jquery',
    'lib/underscore',
    'view/mview',
    'plugin/shop/model/breadcrumb'
], function (app, Sandbox, $, _, MView, modelBreadcrumb) {

    var Breadcrumb = MView.View.extend({

        model: modelBreadcrumb,

        template: 'plugin.shop.component.shoppingCartEmbedded@hbs',

        config: {
            categoryId: false,
            productId: false
        },

        initialize: function () {

            var self = this;

            Sandbox.eventSubscribe("shop:category:changed", function (data) {
                self.options.config.categoryId = data.categoryId;
            });

            Sandbox.eventSubscribe("shop:product:changed", function (data) {
                self.options.config.productId = data.productId;
            });

        }


    });


    return Breadcrumb;

});