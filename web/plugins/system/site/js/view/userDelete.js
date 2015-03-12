define([
    'base/js/view/mView',
    'handlebars',
    'plugins/system/common/js/model/user',
    'text!plugins/system/site/hbs/userDelete.hbs',
    /* lang */
    'i18n!plugins/system/site/nls/translation'
], function (MView, Handlebars, ModelUserInstance, tpl, lang) {

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