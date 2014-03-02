define("plugin/account/js/view/accountProfileDelete", [
    'default/js/lib/sandbox',
    'default/js/view/mView',
    'plugin/account/js/model/account',
    'default/js/plugin/hbs!plugin/account/hbs/accountProfileDelete',
    /* lang */
    'default/js/plugin/i18n!plugin/account/nls/account'
], function (Sandbox, MView, ModelAccountInstance, tpl, lang) {

    var AccountProfileDelete = MView.extend({
        // tagName: 'li',
        // className: 'col-sm-9 col-md-9',
        template: tpl,
        lang: lang,
        model: ModelAccountInstance
    });

    return AccountProfileDelete;

});