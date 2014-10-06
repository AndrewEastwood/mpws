define("plugin/shop/site/js/view/cartEmbedded", [
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'default/js/lib/utils',
    // 'plugin/shop/site/js/collection/listProductCart',
    'default/js/plugin/hbs!plugin/shop/site/hbs/cartEmbedded',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/site/nls/translation'
], function (_, Backbone, Utils, tpl, lang) {

    var CartEmbedded = Backbone.View.extend({
        className: 'btn-group shop-cart-embedded',
        id: 'shop-cart-embedded-ID',
        template: tpl,
        lang: lang,
        initialize: function () {
            this.listenTo(this.model, 'change', this.render);
        },
        render: function () {
            var data = Utils.getHBSTemplateData(this);
            this.$el.html(this.template(Utils.getHBSTemplateData(this)));
        }
    });

    return CartEmbedded;

});