define([
    'backbone',
    'plugins/shop/toolbox/js/model/widgetPrivatBankExchangerates',
    'utils',
    /* template */
    'hbs!plugins/shop/toolbox/hbs/widgetPrivatBankExchangerates',
    /* lang */
    'i18n!plugins/shop/toolbox/nls/translation'
], function (Backbone, ModelWidgetPrivatBankExchangeRate, Utils, tpl, lang) {

    return Backbone.View.extend({
        className: 'panel panel-default',
        lang: lang,
        template: Handlebars.compile(tpl), // check
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