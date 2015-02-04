define("plugin/system/toolbox/js/view/menu", [
    'default/js/lib/backbone',
    'default/js/lib/utils',
    'default/js/plugin/hbs!plugin/system/toolbox/hbs/menu',
    /* lang */
    'default/js/plugin/i18n!plugin/system/toolbox/nls/translation'
], function (Backbone, Utils, tpl, lang) {

    var Menu = Backbone.View.extend({
        tagName: 'li',
        id: 'system-menu-ID',
        attributes: {
            rel: "menu"
        },
        lang: lang,
        template: tpl,
        render: function () {
            this.$el.html(tpl(Utils.getHBSTemplateData(this)));
            return this;
        }
    });

    return Menu;
});