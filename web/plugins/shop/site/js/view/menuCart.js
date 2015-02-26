define([
    'sandbox',
    'backbone',
    'utils',
    // 'plugins/shop/site/js/collection/listProductCart',
    'hbs!plugins/shop/site/hbs/menuCart',
    /* lang */
    'i18n!plugins/shop/site/nls/translation'
], function (Sandbox, Backbone, Utils, tpl, lang) {

    var MenuCart = Backbone.View.extend({
        tagName: 'li',
        template: tpl,
        lang: lang,
        initialize: function () {
            this.model.on('change', this.render, this);
        },
        render: function () {
            this.$el.html(this.template(Utils.getHBSTemplateData(this)));
            if (this.model.getProductCount() && !this.model.isSaved())
                this.$('.counter').text(this.model.get('info').productCount);
            else
                this.$('.counter').empty();
            return this;
        }
    });

    return MenuCart;

});