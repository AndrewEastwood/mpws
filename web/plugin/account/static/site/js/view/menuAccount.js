define("plugin/account/site/js/view/menuAccount", [
    'default/js/lib/sandbox',
    'default/js/lib/backbone',
    'default/js/lib/auth',
    'default/js/lib/utils',
    'default/js/plugin/hbs!plugin/account/site/hbs/menuAccount',
    /* lang */
    'default/js/plugin/i18n!plugin/account/site/nls/translation'
], function (Sandbox, Backbone, Auth, Utils, tpl, lang) {

    var MenuAccount = Backbone.View.extend({
        tagName: 'li',
        className: 'dropdown account-dropdown-signin',
        id: 'account-dropdown-signin-ID',
        template: tpl,
        lang: lang,
        events: {
            "submit .form": 'doSignIn',
            "click #accountProfileSignOutID": 'doSignOut',
        },
        initialize: function () {
            this.listenTo(this.model, "change", this.render);
        },
        doSignIn: function () {
            var authData = this.collectCredentials();
            Auth.signin(authData.email, authData.password, authData.remember, $.proxy(function (authID, response) {
                // this.render();
                this.model.set(response);
            }, this));
            return false;
        },
        doSignOut: function () {
            Auth.signout();
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

    return MenuAccount;
});