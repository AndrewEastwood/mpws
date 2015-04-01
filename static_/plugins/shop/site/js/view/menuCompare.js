define([
    'backbone',
    'handlebars',
    'plugins/shop/site/js/collection/listProductCompare',
    'utils',
    'text!plugins/shop/site/hbs/menuCompare.hbs'
], function (Backbone, Handlebars, CollCompareList, Utils, tpl) {

    var MenuCompare = Backbone.View.extend({
        tagName: 'a',
        template: Handlebars.compile(tpl), // check
        collection: CollCompareList.getInstance(),
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
                href: Handlebars.helpers.bb_link(APP.instances.shop.urls.shopCompare, {asRoot: true})
            });
            return this;
        }
    });

    return MenuCompare;

});