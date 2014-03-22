define("plugin/toolbox/js/view/signin", [
    'default/js/lib/sandbox',
    'default/js/view/mView',
    'plugin/toolbox/js/model/bridge',
    'default/js/plugin/hbs!plugin/toolbox/hbs/toolbox/signin',
    /* lang */
    'default/js/plugin/i18n!plugin/toolbox/nls/toolbox'
], function (Sandbox, MView, ModelAccountInstance, tpl, lang) {

    var SignIn = MView.extend({
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

    return SignIn;

});