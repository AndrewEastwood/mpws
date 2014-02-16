define("plugin/shop/js/view/cartEmbedded", [
    'default/js/lib/underscore',
    'default/js/view/mView',
    'plugin/shop/js/model/cart',
    'default/js/plugin/hbs!plugin/shop/hbs/cartEmbedded'
], function (_, MView, Cart, tpl) {

    var CartEmbedded = MView.extend({
        tagName: 'div',
        className: 'btn-group shop-cart-embedded',
        group: 'shop-cart-embedded-ID',
        model: new Cart(),
        template: tpl,
        initialize: function() {
            MView.prototype.initialize.call(this);
            this.listenTo(this.model, "change", this.render);
        }
    });

    return CartEmbedded;

});