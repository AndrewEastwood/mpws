define([
    'sandbox',
    'base/js/view/mView',
    'plugins/system/common/js/model/user',
    'hbs!plugins/system/site/hbs/userDelete',
    /* lang */
    'i18n!plugins/system/site/nls/translation'
], function (Sandbox, MView, ModelUserInstance, tpl, lang) {

    var UserDelete = MView.extend({
        // tagName: 'li',
        // className: 'col-sm-9 col-md-9',
        template: Handlebars.compile(tpl), // check
        lang: lang,
        model: ModelUserInstance,
        initialize: function () {
            this.model.clearErrors();
            this.model.clearStates();
        }
    });

    return UserDelete;

});