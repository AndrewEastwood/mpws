define("plugin/toolbox/toolbox/js/view/signout", [
    'default/js/lib/sandbox',
    'default/js/view/mView',
    'plugin/toolbox/toolbox/js/model/bridge',
    /* lang */
    'default/js/plugin/i18n!plugin/toolbox/toolbox/nls/translation'
], function (Sandbox, MView, ModelAccountInstance, lang) {

    var SignIn = MView.extend({
        lang: lang,
        model: ModelAccountInstance,
        initialize: function () {
            this.model.clearErrors();
            this.model.clearStates();
        }
    });

    return SignIn;

});