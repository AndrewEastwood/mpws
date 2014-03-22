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
        events: {
            "submit .form": 'doSignIn',
            "click #accountProfileSignOutID": 'doSignOut',
        },
        initialize: function () {
            this.model.clearErrors();
            this.model.clearStates();
        },
        doSignIn: function () {
            this.model.doLogin(this.collectCredentials());
            return false;
        },
        doSignOut: function () {
            this.model.doLogout();
            return false;
        },
        collectCredentials: function () {
            var self = this;
            return {
                email: self.$('#signinEmail').val(),
                password: self.$('#signinPassword').val(),
                remember: self.$('#signinRemember').is(':checked')
            }
        },
    });

    return SignIn;

});