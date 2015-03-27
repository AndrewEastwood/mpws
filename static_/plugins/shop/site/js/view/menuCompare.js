define([
    'backbone',
    'handlebars',
    'plugins/shop/site/js/collection/listProductCompare',
    'utils',
    'text!plugins/shop/site/hbs/menuCompare.hbs'
], function (Backbone, Handlebars, compareCollectionInstance, Utils, tpl) {

    var MenuCompare = Backbone.View.extend({
        tagName: 'a',
        template: Handlebars.compile(tpl), // check
        collection: compareCollectionInstance,
        initialize: function () {
            this.listenTo(compareCollectionInstance, 'reset', this.render);
            this.listenTo(compareCollectionInstance, 'sync', this.render);
        },
        render: function () {
            this.$el.html(this.template(Utils.getHBSTemplateData(this)));
            if (compareCollectionInstance.length)
                this.$('.counter').text(compareCollectionInstance.length);
            else
                this.$('.counter').empty();
            this.$el.attr({
                href: Handlebars.helpers.bb_link(APP.instances.shop.urls.shopСompare, {asRoot: true})
            });
            return this;
        }
    });

    return MenuCompare;

});