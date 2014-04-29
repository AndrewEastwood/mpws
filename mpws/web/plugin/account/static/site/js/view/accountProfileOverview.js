define("plugin/account/site/js/view/accountProfileOverview", [
    'default/js/lib/sandbox',
    'default/js/view/mView',
    'plugin/account/common/js/model/account',
    'default/js/plugin/hbs!plugin/account/site/hbs/accountProfileOverview',
    /* lang */
    'default/js/plugin/i18n!plugin/account/site/nls/translation'
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