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
            // extend parent
            MView.prototype.initialize.call(this, options);
        },

        renderLocation: function (productID, categoryID) {
            app.log(true, 'Breadcrumb view: renderLocation', productID, categoryID);
            this.model.setUrlData({
                productId: productID || null,
                categoryId: categoryID || null
            });
        }

    });

    return Breadcrumb;

});