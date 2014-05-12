define("plugin/account/toolbox/js/view/menu", [
    'default/js/lib/sandbox',
    'default/js/view/mView',
    'default/js/plugin/hbs!plugin/account/toolbox/hbs/menu',
    /* lang */
    'default/js/plugin/i18n!plugin/account/toolbox/nls/translation',
], function (Sandbox, MView, tpl, lang) {

    var toolboxMenuAccount = new (MView.extend({
        id: 'account-menu-ID',
        lang: lang,
        template: tpl,
    }))();

    // debugger;
    Sandbox.eventSubscribe('plugin:toolbox:menu:ready', function () {
        toolboxMenuAccount.render();
        Sandbox.eventNotify('plugin:toolbox:menu:display', {
            el: toolboxMenuAccount.$el,
            append: true,
            keepExisted: true
        });
    });

});