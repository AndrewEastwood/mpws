define("plugin/shop/site/js/view/productItemShort", [
    'default/js/lib/sandbox',
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'default/js/lib/utils',
    'default/js/plugin/hbs!plugin/shop/site/hbs/productItemShort',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/site/nls/translation'
], function (Sandbox, _, Backbone, Utils, tpl, lang) {

    var ProductItemShort = Backbone.View.extend({
        className: 'shop-product-item shop-product-item-short col-xs-12 col-sm-6 col-md-3 col-lg-3',
        template: tpl,
        lang: lang,
        initialize: function () {
            _.bindAll(this, 'refresh');

            Sandbox.eventSubscribe('plugin:shop:list_wish:changed', this.refresh);
            Sandbox.eventSubscribe('plugin:shop:list_compare:changed', this.refresh);

            if (this.model)
                this.listenTo(this.model, 'change', this.render);
        },
        refresh: function (data) {
            if (this.model) {
                if (data && data.id && (data.id === this.model.id || data.id === "*")) {
                    this.model.fetch();
                }
            }
        },
        render: function () {
            this.$el.html(this.template(Utils.getHBSTemplateData(this)));
            return this;
        }
    });

    return ProductItemShort;

});