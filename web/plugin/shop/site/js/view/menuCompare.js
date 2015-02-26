define("plugin/shop/site/js/view/menuCompare", [
    'default/js/lib/sandbox',
    'default/js/lib/backbone',
    'plugin/shop/site/js/collection/listProductCompare',
    'default/js/lib/utils',
    'default/js/plugin/hbs!plugin/shop/site/hbs/menuCompare'
], function (Sandbox, Backbone, compareCollectionInstance, Utils, tpl) {

    var MenuCompare = Backbone.View.extend({
        tagName: 'li',
        template: tpl,
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