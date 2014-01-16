define("plugin/shop/js/view/breadcrumb", [
    'cmn_jquery',
    'default/js/lib/underscore',
    'default/js/view/mview',
    'plugin/shop/js/model/breadcrumb',
    'default/js/lib/bootstrap'
], function ($, _, MView, modelBreadcrumb) {

    var Breadcrumb = MView.extend({

        name: "shopBreadcrumb",

        model: new modelBreadcrumb(),

        template: 'plugin/shop/hbs/component/breadcrumb.hbs',

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