define("plugin/account/js/view/toolbox/menu", [
    'default/js/lib/sandbox',
    'customer/js/site',
    'default/js/view/mView',
    'default/js/plugin/hbs!plugin/account/hbs/toolbox/menu',
    /* lang */
    'default/js/plugin/i18n!plugin/account/nls/site',
], function (Sandbox, Site, MView, tpl, lang) {

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