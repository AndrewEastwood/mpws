define("plugin/shop/site/js/view/menuCart", [
    'default/js/lib/sandbox',
    'default/js/lib/backbone',
    'default/js/lib/utils',
    'plugin/shop/site/js/collection/listProductCart',
    'default/js/plugin/hbs!plugin/shop/site/hbs/menuCart',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/site/nls/translation'
], function (Sandbox, Backbone, Utils, cartCollectionInstance, tpl, lang) {

    var MenuCart = Backbone.View.extend({
        tagName: 'li',
        template: tpl,
        lang: lang,
        collection: cartCollectionInstance,
        initialize: function () {
            this.listenTo(cartCollectionInstance, 'reset', this.render);
            this.listenTo(cartCollectionInstance, 'sync', this.render);
        },
        render: function () {
            this.$el.html(this.template(Utils.getHBSTemplateData(this)));
            if (cartCollectionInstance.length)
                this.$('.counter').text(cartCollectionInstance.length);
            else
                this.$('.counter').empty();
            return this;
        }
    });

    return MenuCart;

});