define("plugin/toolbox/toolbox/js/view/signin", [
    'default/js/lib/sandbox',
    'default/js/view/mView',
    'plugin/toolbox/toolbox/js/model/auth',
    'default/js/plugin/hbs!plugin/toolbox/toolbox/hbs/signin',
    /* lang */
    'default/js/plugin/i18n!plugin/toolbox/toolbox/nls/translation'
], function (Sandbox, MView, ModelAuthInstance, tpl, lang) {

    var SignIn = MView.extend({
        tagName: 'form',
        className: 'form form-horizontal toolbox-component-form-signin',
        template: tpl,
        lang: lang,
        model: ModelAuthInstance,
        events: {
            "submit": 'doSignIn',
            "click #accountProfileSignOutID": 'doSignOut',
        },
        initialize: function () {
            MView.prototype.initialize.call(this);
            this.model.clearErrors();
            this.model.clearStates();
            this.listenTo(this.model, "change", this.render);
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
        }
    });

    return SignIn;

});