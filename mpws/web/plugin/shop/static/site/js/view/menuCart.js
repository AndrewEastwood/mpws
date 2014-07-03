define("plugin/shop/site/js/view/menuCart", [
    'default/js/lib/sandbox',
    'default/js/lib/backbone',
    'default/js/lib/utils',
    // 'plugin/shop/site/js/collection/listProductCart',
    'default/js/plugin/hbs!plugin/shop/site/hbs/menuCart',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/site/nls/translation'
], function (Sandbox, Backbone, Utils, tpl, lang) {

    var MenuCart = Backbone.View.extend({
        tagName: 'li',
        template: tpl,
        lang: lang,
        // collection: cartCollectionInstance,
        initialize: function () {
            // this.listenTo(cartCollectionInstance, 'reset', this.render);
            // this.listenTo(cartCollectionInstance, 'sync', this.render);
            // debugger;
            this.model.on('change', this.render, this);
        },
        render: function () {
            // debugger;
            this.$el.html(this.template(Utils.getHBSTemplateData(this)));
            if (this.model.getProductCount())
                this.$('.counter').text(this.model.getProductCount());
            else
                this.$('.counter').empty();
            return this;
        }
    });

    return MenuCart;

});