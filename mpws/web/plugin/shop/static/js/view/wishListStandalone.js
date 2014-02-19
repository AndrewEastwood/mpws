define("plugin/shop/js/view/wishListStandalone", [
    'default/js/lib/sandbox',
    'default/js/lib/underscore',
    'default/js/view/mView',
    'default/js/plugin/hbs!plugin/shop/hbs/wishListStandalone',
    "default/js/lib/jquery.cookie"
], function (Sandbox, _, MView, tpl) {

    var WishList = MView.extend({
        className: 'row shop-wishlist',
        group: 'shop-cart-wishlist-ID',
        template: tpl,
        initialize: function() {
            MView.prototype.initialize.call(this);
            this.listenTo(this.model, "change", this.render);
        }
    });

    return WishList;

});