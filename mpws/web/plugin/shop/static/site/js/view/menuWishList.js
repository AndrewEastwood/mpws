define("plugin/shop/site/js/view/menuWishList", [
    'default/js/lib/sandbox',
    'default/js/lib/backbone',
    'plugin/shop/site/js/collection/listProductWish',
    'default/js/lib/utils',
    'default/js/plugin/hbs!plugin/shop/site/hbs/menuWishList'
], function (Sandbox, Backbone, wishCollectionInstance, Utils, tpl) {

    var MenuWishList = Backbone.View.extend({
        tagName: 'li',
        template: tpl,
        collection: wishCollectionInstance,
        initialize: function () {
            // var _self = this;
            // Sandbox.eventSubscribe('plugin:shop:wishlist:info', function (data) {
            // });
            // wishCollectionInstance.getInfo();
            this.listenTo(wishCollectionInstance, 'reset', this.updateCounter);
            this.listenTo(wishCollectionInstance, 'sync', this.updateCounter);
        },
        render: function () {
            this.$el.html(this.template(Utils.getHBSTemplateData(this)));
            return this;
        },
        updateCounter: function() {
            if (wishCollectionInstance.length)
                this.$('.counter').text(wishCollectionInstance.length);
            else
                this.$('.counter').empty();
        }
    });

    return MenuWishList;

});