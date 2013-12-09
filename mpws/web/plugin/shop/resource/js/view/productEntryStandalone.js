APP.Modules.register("plugin/shop/view/productEntryStandalone", [], [
    'lib/jquery',
    'view/mview',
    'plugin/shop/model/productEntryStandalone',
], function (app, Sandbox, $, MView, modelProductEntryStandalone) {

    var ProductEntryStandalone = MView.extend({

        name: "productEntryStandalone",

        model: new modelProductEntryStandalone(),

        template: 'plugin.shop.component.productEntryStandalone@hbs',

        initialize: function (options) {

            var _self = this;
            // extend parent
            MView.prototype.initialize.call(this, options);
            // app.log('view ProductEntryStandalone initialize', this);

            // apply visual effects
            $(options.el).on('mview:rendered', function (event, senderName) {
                if (!_self.options.name != senderName)
                    return;
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