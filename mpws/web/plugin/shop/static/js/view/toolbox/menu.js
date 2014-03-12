define("plugin/shop/js/view/toolbox/menu", [
    'default/js/lib/sandbox',
    'customer/js/site',
    'default/js/view/mView',
    'default/js/plugin/hbs!plugin/shop/hbs/toolbox/menu',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/nls/toolbox',
], function (Sandbox, Site, MView, tpl, lang) {

    var menu = new (MView.extend({
        lang: lang,
        template: tpl,
    }))();
    menu.render();

    // debugger;
    Sandbox.eventSubscribe('global:loader:complete', function () {
        // debugger;
        Sandbox.eventNotify('site:content:render', {
            name: 'CommmonToolboxMenu',
            el: menu.$el,
            append: true
        });
    });

});