define("plugin/shop/js/view/productEntryStandalone", [
    'cmn_jquery',
    'default/js/view/mview',
    'plugin/shop/js/model/productEntryStandalone',
    /* ui components */
    'default/js/lib/bootstrap',
    'default/js/lib/bootstrap-combobox',
    'default/js/lib/bootstrap-magnify',
    'default/js/lib/lightbox'
], function ($, MView, modelProductEntryStandalone) {

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