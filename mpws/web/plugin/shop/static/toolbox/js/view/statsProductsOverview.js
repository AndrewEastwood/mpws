define("plugin/shop/toolbox/js/view/statsProductsOverview", [
    'default/js/lib/backbone',
    'plugin/shop/toolbox/js/model/statsProductsOverview',
    'default/js/lib/utils',
    /* template */
    'default/js/plugin/hbs!plugin/shop/toolbox/hbs/statsProductsOverview',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/toolbox/nls/translation'
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