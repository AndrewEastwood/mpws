define([
    'backbone',
    'handlebars',
    'plugins/shop/toolbox/js/model/widgetPrivatBankExchangerates',
    'utils',
    /* template */
    'text!plugins/shop/toolbox/hbs/widgetPrivatBankExchangerates.hbs',
    /* lang */
    'i18n!plugins/shop/toolbox/nls/translation'
], function (Backbone, Handlebars, ModelWidgetPrivatBankExchangeRate, Utils, tpl, lang) {

    return Backbone.View.extend({
        className: 'panel panel-default',
        lang: lang,
        template: Handlebars.compile(tpl), // check
        initialize: function () {
            this.model = new ModelWidgetPrivatBankExchangeRate();
            this.listenTo(this.model, 'change', this.render);
        },
        render: function () {
            this.$el.html(this.template(Utils.getHBSTemplateData(this)));
            return this;
        }
    });
});