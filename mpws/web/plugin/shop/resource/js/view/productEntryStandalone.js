APP.Modules.register("plugin/shop/view/productEntryStandalone", [], [
    'lib/jquery',
    'lib/underscore',
    'view/mview',
    'lib/mpws.page',
    'plugin/shop/model/productEntryStandalone',
], function (app, Sandbox, $, _, MView, mpwsPage, modelProductEntryStandalone) {

    var ProductEntryStandalone = MView.extend({

        name: "productEntryStandalone",

        model: new modelProductEntryStandalone(),

        template: 'plugin.shop.component.productEntryStandalone@hbs',

        initialize: function (options) {

            // extend parent
            MView.prototype.initialize.call(this, options);
            // app.log('view ProductEntryStandalone initialize', this);

            // apply visual effects
            $(options.el).on('mview:rendered', function (event) {
                // app.log(event);
                $(event.target).find('.shop-product-image-main .image').magnify();
            });
        },

        renderProductByID: function (productID) {
            app.log(true, 'renderProductByID', productID);
            this.model.setUrlData('productId', productID);
        }

    });

    return ProductEntryStandalone;

});