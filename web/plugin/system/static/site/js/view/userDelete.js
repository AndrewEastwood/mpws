define("plugin/system/site/js/view/userDelete", [
    'default/js/lib/sandbox',
    'default/js/view/mView',
    'plugin/system/common/js/model/user',
    'default/js/plugin/hbs!plugin/system/site/hbs/userDelete',
    /* lang */
    'default/js/plugin/i18n!plugin/system/site/nls/translation'
], function (Sandbox, MView, ModelUserInstance, tpl, lang) {

    var UserDelete = MView.extend({
        // tagName: 'li',
        // className: 'col-sm-9 col-md-9',
        template: tpl,
        lang: lang,
        model: ModelUserInstance,
        initialize: function () {
            this.model.clearErrors();
            this.model.clearStates();
        }
    });

    return UserDelete;

});