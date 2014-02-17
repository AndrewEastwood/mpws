define("plugin/shop/js/view/cartStandalone", [
    'default/js/lib/underscore',
    'default/js/view/mView',
    'default/js/plugin/hbs!plugin/shop/hbs/cartStandalone'
], function (_, MView, tpl) {

    var CartEmbedded = MView.extend({
        // tagName: 'div',
        className: 'row shop-cart-standalone',
        group: 'shop-cart-standalone-ID',
        template: tpl,
        initialize: function() {
            MView.prototype.initialize.call(this);
            this.listenTo(this.model, "change", this.render);
        }
    });

    return CartEmbedded;

});