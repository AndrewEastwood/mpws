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

    // debugger;
    Sandbox.eventSubscribe('plugin:toolbox:render:complete', function () {
        // debugger;
        menu.render();

        Sandbox.eventNotify('plugin:toolbox:menu:display', {
            name: 'CommonBodyLeft',
            el: menu.$el,
            append: true,
            keepExisted: true
        });
    });

});