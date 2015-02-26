define([
    'backbone',
    'plugins/shop/toolbox/js/model/statsProductsOverview',
    'utils',
    /* template */
    'hbs!plugins/shop/toolbox/hbs/statsProductsOverview',
    /* lang */
    'i18n!plugins/shop/toolbox/nls/translation'
], function (Backbone, ModelProductsOverview, Utils, tpl, lang) {

    return Backbone.View.extend({
        className: 'panel panel-default',
        lang: lang,
        template: tpl,
        initialize: function () {
            this.model = new ModelProductsOverview();
            this.listenTo(this.model, 'change', this.render);
        },
        render: function () {
            this.$el.html(tpl(Utils.getHBSTemplateData(this)));
            return this;
        }
    });
});