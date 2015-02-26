define("plugin/system/toolbox/js/view/menu", [
    'default/js/lib/backbone',
    'default/js/lib/utils',
    'default/js/plugin/hbs!plugin/system/toolbox/hbs/menu',
    /* lang */
    'default/js/plugin/i18n!plugin/system/toolbox/nls/translation'
], function (Backbone, Utils, tpl, lang) {

    var Menu = Backbone.View.extend({
        lang: lang,
        template: tpl,
        initialize: function () {
            this.listenTo(this.model, 'change', this.render);
        },
        render: function () {
            this.$el.html(tpl(Utils.getHBSTemplateData(this)));
            this.$el = $(this.$el.html());
            return this;
        }
    });

    return Menu;
});