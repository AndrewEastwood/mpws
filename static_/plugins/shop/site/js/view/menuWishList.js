define([
    'backbone',
    'handlebars',
    'plugins/shop/site/js/collection/listProductWish',
    'utils',
    'text!plugins/shop/site/hbs/menuWishList.hbs'
], function (Backbone, Handlebars, CollWishList, Utils, tpl) {

    var MenuWishList = Backbone.View.extend({
        tagName: 'a',
        template: Handlebars.compile(tpl), // check
        collection: CollWishList.getInstance(),
        initialize: function () {
            this.listenTo(this.collection, 'reset', this.render);
            this.listenTo(this.collection, 'sync', this.render);
        },
        render: function () {
            this.$el.html(this.template(Utils.getHBSTemplateData(this)));
            if (this.collection.length)
                this.$('.counter').text(this.collection.length);
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