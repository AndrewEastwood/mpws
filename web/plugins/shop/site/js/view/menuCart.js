define([
    'backbone',
    'handlebars',
    'utils',
    // 'plugins/shop/site/js/collection/listProductCart',
    'text!plugins/shop/site/hbs/menuCart.hbs',
    /* lang */
    'i18n!plugins/shop/site/nls/translation'
], function (Backbone, Handlebars, Utils, tpl, lang) {

    var MenuCart = Backbone.View.extend({
        tagName: 'li',
        template: Handlebars.compile(tpl), // check
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