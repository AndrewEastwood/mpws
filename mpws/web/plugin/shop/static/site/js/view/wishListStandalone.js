define("plugin/shop/site/js/view/wishListStandalone", [
    'default/js/lib/sandbox',
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'plugin/shop/site/js/collection/listProductWish',
    'default/js/lib/utils',
    'default/js/plugin/hbs!plugin/shop/site/hbs/wishListStandalone',
    "default/js/lib/jquery.cookie"
], function (Sandbox, _, Backbone, wishCollectionInstance, Utils, tpl) {

    var WishList = Backbone.View.extend({
        collection: wishCollectionInstance,
        className: 'row shop-wishlist-standalone',
        id: 'shop-cart-wishlist-ID',
        template: tpl,
        initialize: function () {
            this.listenTo(this.collection, 'reset', this.render);
            this.listenTo(this.collection, 'sync', this.render);
        },
        render: function() {
            this.$el.html(this.template(Utils.getHBSTemplateData(this)));
            return this;
        }
    });

    return WishList;

});