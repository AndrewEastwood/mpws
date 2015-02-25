define("plugin/shop/toolbox/js/view/widgetPrivatBankExchangerates", [
    'default/js/lib/backbone',
    'plugin/shop/toolbox/js/model/widgetPrivatBankExchangerates',
    'default/js/lib/utils',
    /* template */
    'default/js/plugin/hbs!plugin/shop/toolbox/hbs/widgetPrivatBankExchangerates',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/toolbox/nls/translation'
], function (Backbone, ModelWidgetPrivatBankExchangeRate, Utils, tpl, lang) {

    return Backbone.View.extend({
        className: 'panel panel-default',
        lang: lang,
        template: tpl,
        initialize: function () {
            this.model = new ModelWidgetPrivatBankExchangeRate();
            this.listenTo(this.model, 'change', this.render);
        },
        render: function () {
            this.$el.html(tpl(Utils.getHBSTemplateData(this)));
            return this;
        }
    });
});