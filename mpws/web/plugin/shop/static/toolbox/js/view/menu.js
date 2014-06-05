define("plugin/shop/toolbox/js/view/menu", [
    'default/js/lib/sandbox',
    'default/js/view/mView',
    'default/js/plugin/hbs!plugin/shop/toolbox/hbs/menu',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/toolbox/nls/translation',
], function (Sandbox, MView, tpl, lang) {

    var menu = new (MView.extend({
        id: 'shop-menu-ID',
        lang: lang,
        template: tpl
    }))();

    Sandbox.eventSubscribe('customer:menu:ready', function (CustomerMenuView) {
        menu.render();
        CustomerMenuView.$('[name="PluginMenuList"]').append(menu.$el);
    });

});