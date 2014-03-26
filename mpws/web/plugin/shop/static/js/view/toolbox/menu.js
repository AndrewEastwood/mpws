define("plugin/shop/js/view/toolbox/menu", [
    'default/js/lib/sandbox',
    'customer/js/site',
    'default/js/view/mView',
    'default/js/plugin/hbs!plugin/shop/hbs/toolbox/menu',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/nls/toolbox',
], function (Sandbox, Site, MView, tpl, lang) {

    var menu = new (MView.extend({
        id: 'shop-menu-ID',
        lang: lang,
        template: tpl
    }))();

    // debugger;
    Sandbox.eventSubscribe('plugin:toolbox:render:complete', function () {
        // debugger;
        menu.render();

        Sandbox.eventNotify('plugin:toolbox:menu:display', {
            name: 'CommmonToolboxMenu',
            el: menu.$el,
            append: true,
            keepExisted: true
        });
    });

});