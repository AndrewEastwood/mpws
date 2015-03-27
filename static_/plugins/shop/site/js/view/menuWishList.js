define([
    'backbone',
    'handlebars',
    'plugins/shop/site/js/collection/listProductWish',
    'utils',
    'text!plugins/shop/site/hbs/menuWishList.hbs'
], function (Backbone, Handlebars, wishCollectionInstance, Utils, tpl) {

    var MenuWishList = Backbone.View.extend({
        tagName: 'a',
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
            this.$el.attr({
                href: Handlebars.helpers.bb_link(APP.instances.shop.urls.shopWishlist, {asRoot: true})
            });
            return this;
        }
    });

    return MenuWishList;

});