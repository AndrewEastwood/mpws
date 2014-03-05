define("plugin/shop/js/view/cartEmbedded", [
    'default/js/lib/underscore',
    'default/js/view/mView',
    'plugin/shop/js/model/cart',
    'default/js/plugin/hbs!plugin/shop/hbs/cartEmbedded'
], function (_, MView, ModelCartInstance, tpl) {

    var CartEmbedded = MView.extend({
        // tagName: 'div',
        model: ModelCartInstance,
        className: 'btn-group shop-cart-embedded',
        id: 'shop-cart-embedded-ID',
        template: tpl,
        initialize: function() {
            MView.prototype.initialize.call(this);
            this.listenTo(this.model, "change", this.render);
        }
    });

    return CartEmbedded;

});