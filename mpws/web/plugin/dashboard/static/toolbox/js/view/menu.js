define("plugin/dashboard/toolbox/js/view/menu", [
    'default/js/lib/sandbox',
    'default/js/lib/backbone',
    'default/js/lib/utils',
    /* template */
    'default/js/plugin/hbs!plugin/dashboard/toolbox/hbs/menu',
    /* lang */
    'default/js/plugin/i18n!plugin/dashboard/toolbox/nls/translation',
], function (Sandbox, Backbone, Utils, tpl, lang) {

    var menu = new (Backbone.View.extend({
        tagName: 'li',
        id: 'dashboard-menu-ID',
        className: 'menu-dashboard-dashboard',
        attributes: {
            rel: "menu"
        },
        lang: lang,
        template: tpl,
        render: function () {
            this.$el.html(tpl(Utils.getHBSTemplateData(this)));
        }
    }))();

    Sandbox.eventSubscribe('global:loader:complete', function (CustomerMenuView) {
        menu.render();
        Sandbox.eventNotify('global:content:render', {
            name: 'MenuLeft',
            el: menu.$el,
            prepend: true
        });
    });

});