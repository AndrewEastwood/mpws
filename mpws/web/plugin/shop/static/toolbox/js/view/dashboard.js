define("plugin/shop/toolbox/js/view/dashboard", [
    'default/js/lib/sandbox',
    'default/js/view/mView',
    'plugin/shop/toolbox/js/model/orderEntry',
    'default/js/lib/bootstrap-dialog',
    /* template */
    'default/js/plugin/hbs!plugin/shop/toolbox/hbs/dashboard',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/toolbox/nls/translation',
    // "default/js/lib/select2/select2",
    'default/js/lib/bootstrap-editable'
], function (Sandbox, MView, ModelOrderEntry, BootstrapDialog, tpl, lang) {

    return MView.extend({
        id: 'shop-menu-ID',
        lang: lang,
        template: tpl
    });
});