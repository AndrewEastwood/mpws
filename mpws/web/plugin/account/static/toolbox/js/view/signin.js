define("plugin/account/toolbox/js/view/signin", [
    'default/js/lib/sandbox',
    'default/js/lib/backbone',
    'default/js/lib/utils',
    'default/js/plugin/hbs!plugin/account/toolbox/hbs/signin',
    /* lang */
    'default/js/plugin/i18n!plugin/account/toolbox/nls/translation'
], function (Sandbox, Backbone, Utils, tpl, lang) {

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
        },
        render: function () {
            this.$el.html(tpl(Utils.getHBSTemplateData(this)));
        }
    });

    return SignIn;

});