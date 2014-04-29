define("plugin/shop/site/js/view/wishListStandalone", [
    'default/js/lib/sandbox',
    'default/js/lib/underscore',
    'default/js/view/mView',
    'plugin/shop/site/js/model/wishList',
    'default/js/plugin/hbs!plugin/shop/site/hbs/wishListStandalone',
    "default/js/lib/jquery.cookie"
], function (Sandbox, _, MView, ModelWishListInstance, tpl) {

    var WishList = MView.extend({
        model: ModelWishListInstance,
        className: 'row shop-wishlist-standalone',
        id: 'shop-cart-wishlist-ID',
        template: tpl,
        initialize: function() {
            MView.prototype.initialize.call(this);
            this.listenTo(this.model, "change", this.render);
        }
    });

    return WishList;

});