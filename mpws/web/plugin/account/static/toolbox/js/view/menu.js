define("plugin/account/toolbox/js/view/menu", [
    'default/js/lib/sandbox',
    'default/js/view/mView',
    'default/js/plugin/hbs!plugin/account/toolbox/hbs/menu',
    /* lang */
    'default/js/plugin/i18n!plugin/account/toolbox/nls/translation',
], function (Sandbox, MView, tpl, lang) {

    var toolboxMenuAccount = new (MView.extend({
        lang: lang,
        template: tpl,
    }))();
    toolboxMenuAccount.render();

    // debugger;
    Sandbox.eventSubscribe('global:loader:complete', function () {
        Sandbox.eventNotify('global:content:render', {
            name: 'CommmonToolboxMenu',
            el: toolboxMenuAccount.$el,
            append: true
        });
    });

});