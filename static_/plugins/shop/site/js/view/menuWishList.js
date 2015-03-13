define([
    'backbone',
    'handlebars',
    'plugins/shop/site/js/collection/listProductWish',
    'utils',
    'text!plugins/shop/site/hbs/menuWishList.hbs'
], function (Backbone, Handlebars, wishCollectionInstance, Utils, tpl) {

    var MenuWishList = Backbone.View.extend({
        tagName: 'li',
        template: Handlebars.compile(tpl), // check
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