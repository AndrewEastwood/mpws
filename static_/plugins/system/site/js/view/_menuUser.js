define([
    'backbone',
    'handlebars',
    'auth',
    'utils',
    'text!plugins/system/site/hbs/menuUser.hbs',
    /* lang */
    'i18n!plugins/system/site/nls/translation'
], function (Backbone, Handlebars, Auth, Utils, tpl, lang) {

    var MenuAccount = Backbone.View.extend({
        tagName: 'li',
        className: 'dropdown account-dropdown-signin',
        id: 'account-dropdown-signin-ID',
        template: Handlebars.compile(tpl), // check
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
            this.$el.html(this.template(Utils.getHBSTemplateData(this)));
            return this;
        }
    });

    return MenuAccount;
});