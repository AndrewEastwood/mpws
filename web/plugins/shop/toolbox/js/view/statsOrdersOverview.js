define([
    'backbone',
    'handlebars',
    'plugins/shop/toolbox/js/model/statsOrdersOverview',
    'utils',
    /* template */
    'text!plugins/shop/toolbox/hbs/statsOrdersOverview.hbs',
    /* lang */
    'i18n!plugins/shop/toolbox/nls/translation'
], function (Backbone, Handlebars, ModelOrdersOverview, Utils, tpl, lang) {

    return Backbone.View.extend({
        className: 'panel panel-default',
        lang: lang,
        template: Handlebars.compile(tpl), // check
        initialize: function () {
            this.model = new ModelOrdersOverview();
            this.listenTo(this.model, 'change', this.render);
        },
        render: function () {
            this.$el.html(this.template(Utils.getHBSTemplateData(this)));
            return this;
        }
    });
});