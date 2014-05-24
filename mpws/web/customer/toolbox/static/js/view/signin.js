define("customer/js/view/signin", [
    'default/js/lib/sandbox',
    'default/js/lib/backbone',
    'plugin/toolbox/toolbox/js/model/auth',
    'default/js/plugin/hbs!customer/hbs/signin',
    /* lang */
    'default/js/plugin/i18n!customer/nls/translation'
], function (Sandbox, Backbone, tpl, lang) {

    var SignIn = Backbone.View.extend({
        tagName: 'form',
        className: 'form form-horizontal toolbox-component-form-signin',
        template: tpl,
        lang: lang,
        events: {
            "submit": 'doSignIn',
            "click #accountProfileSignOutID": 'doSignOut',
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