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