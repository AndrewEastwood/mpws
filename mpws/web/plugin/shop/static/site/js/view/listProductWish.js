define("plugin/shop/site/js/view/listProductWish", [
    'default/js/lib/backbone',
    'plugin/shop/site/js/collection/listProductWish',
    'default/js/lib/utils',
    'default/js/plugin/hbs!plugin/shop/site/hbs/listProductWish',
    "default/js/lib/jquery.cookie"
], function (Backbone, wishCollectionInstance, Utils, tpl) {

    var ListProductWish = Backbone.View.extend({
        collection: wishCollectionInstance,
        className: 'row shop-wishlist-standalone clearfix',
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

    return ListProductWish;

});