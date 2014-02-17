define("plugin/shop/js/view/productItemFull", [
    'default/js/lib/sandbox',
    'default/js/view/mView',
    'plugin/shop/js/model/productItemFull',
    'default/js/plugin/hbs!plugin/shop/hbs/productItemFull',
    /* enhanced ui */
    'default/js/lib/bootstrap-magnify',
    'default/js/lib/lightbox'
], function (Sandbox, MView, ProductItemFull, tpl) {

    var ProductItemFull = MView.extend({
        tagName: 'div',
        className: 'shop-product-item shop-product-item-full',
        model: new ProductItemFull(),
        template: tpl,
        initialize: function () {
            this.on('mview:renderComplete', function () {
                this.$('.shop-product-image-main img').magnify();
            }, this);
        },
    });

    return ProductItemFull;

});