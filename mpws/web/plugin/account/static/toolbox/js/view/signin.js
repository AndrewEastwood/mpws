define("plugin/account/toolbox/js/view/signin", [
    'default/js/lib/sandbox',
    'default/js/lib/backbone',
    'default/js/lib/auth',
    'default/js/lib/utils',
    'default/js/plugin/hbs!plugin/account/toolbox/hbs/signin',
    /* lang */
    'default/js/plugin/i18n!plugin/account/toolbox/nls/translation'
], function (Sandbox, Backbone, Auth, Utils, tpl, lang) {

    var SignIn = Backbone.View.extend({
        tagName: 'form',
        className: 'form form-horizontal toolbox-component-form-signin',
        template: tpl,
        lang: lang,
        events: {
            "submit": 'doSignIn'
        },
        doSignIn: function () {
            var authData = this.collectCredentials();
            Auth.signin(authData.email, authData.password, authData.remember);
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
        render: function () {
            var self = this;
            // Auth.getStatus().done(function(){
                self.$el.html(tpl(Utils.getHBSTemplateData(self)));
            // });
        }
    });

    return SignIn;

});