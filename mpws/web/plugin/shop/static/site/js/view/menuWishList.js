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
            this.listenTo(wishCollectionInstance, 'change', function(){
                // var _count = data && data.products && data.products.length || 0;
                if (wishCollectionInstance.length)
                    _self.$('.counter').text(wishCollectionInstance.length);
                else
                    _self.$('.counter').empty();
            });
        },
        render: function () {
            this.$el.html(this.template(Utils.getHBSTemplateData(this)));
            return this;
        }
    });

    return MenuWishList;

});