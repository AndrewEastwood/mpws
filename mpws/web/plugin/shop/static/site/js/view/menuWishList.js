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
            this.listenTo(wishCollectionInstance, 'reset', this.render);
            this.listenTo(wishCollectionInstance, 'sync', this.render);
        },
        render: function () {
            this.$el.html(this.template(Utils.getHBSTemplateData(this)));
            if (wishCollectionInstance.length)
                this.$('.counter').text(wishCollectionInstance.length);
            else
                this.$('.counter').empty();
            return this;
        }
    });

    return MenuWishList;

});