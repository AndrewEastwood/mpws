define([
    'sandbox',
    'backbone',
    'plugins/shop/site/js/collection/listProductCompare',
    'utils',
    'hbs!plugins/shop/site/hbs/menuCompare'
], function (Sandbox, Backbone, compareCollectionInstance, Utils, tpl) {

    var MenuCompare = Backbone.View.extend({
        tagName: 'li',
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
            return this;
        }
    });

    return MenuCompare;

});