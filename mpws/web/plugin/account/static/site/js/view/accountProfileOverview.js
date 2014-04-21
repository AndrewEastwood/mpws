define("plugin/account/js/view/site/accountProfileOverview", [
    'default/js/lib/sandbox',
    'default/js/view/mView',
    'plugin/account/js/model/account',
    'default/js/plugin/hbs!plugin/account/hbs/site/accountProfileOverview',
    /* lang */
    'default/js/plugin/i18n!plugin/account/nls/site'
], function (Sandbox, MView, ModelAccountInstance, tpl, lang) {

    var AccountProfileOverview = MView.extend({
        // tagName: 'li',
        // className: 'col-sm-9 col-md-9',
        template: tpl,
        lang: lang,
        model: ModelAccountInstance,
        initialize: function () {
            this.model.clearErrors();
            this.model.clearStates();
        }
    });

    return AccountProfileOverview;

});