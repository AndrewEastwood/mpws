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
            this.$('.counter').addClass('hidden');
            if (this.collection.length) {
                this.$('.counter').removeClass('hidden');
                this.$('.counter .value').text(this.collection.length);
            }
            this.$el.attr({
                href: Handlebars.helpers.bb_link(APP.instances.shop.urls.shopWishlist, {asRoot: true})
            });
            return this;
        }
    });

    return MenuWishList;

});