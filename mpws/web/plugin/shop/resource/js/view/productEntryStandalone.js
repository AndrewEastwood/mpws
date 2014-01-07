APP.Modules.register("plugin/shop/view/productEntryStandalone", [], [
    'lib/jquery',
    'view/mview',
    'plugin/shop/model/productEntryStandalone',
    /* ui components */
    'lib/bootstrap',
    'lib/bootstrap-combobox',
    'lib/bootstrap-magnify',
    'lib/lightbox'
], function (app, Sandbox, $, MView, modelProductEntryStandalone) {

    var ProductEntryStandalone = MView.extend({

        name: "productEntryStandalone",

        model: new modelProductEntryStandalone(),

        template: 'plugin.shop.component.productEntryStandalone@hbs',

        initialize: function (options) {

            var _self = this;
            // extend parent
            // app.log('view productEntryStandalone initialize', options);
            MView.prototype.initialize.call(this, options);

            // apply visual effects
            _self.on('mview:rendered', function (event, senderName) {
                // app.log('!!!!!!!!!!!!!!!!!!!!!!!', event, senderName, _self);
                _self.$el.find('.shop-product-image-main .image').magnify();
                // if (!_self.options.name != senderName)
                //     return;
            });
        },

        renderProductByID: function (productID) {
            app.log(true, 'renderProductByID', productID);
            this.model.setUrlData('productId', productID);
        }

    });

    return ProductEntryStandalone;

});