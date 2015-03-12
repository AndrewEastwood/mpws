define([
    'jquery',
    'underscore',
    'handlebars',
    'backbone',
    'auth',
    'utils',
    'text!plugins/system/toolbox/hbs/signin.hbs',
    /* lang */
    'i18n!plugins/system/toolbox/nls/translation'
], function ($, _, Handlebars, Backbone, Auth, Utils, tpl, lang) {

    var SignIn = Backbone.View.extend({
        tagName: 'form',
        className: 'form form-horizontal toolbox-component-form-signin',
        template: Handlebars.compile(tpl), // check
        lang: lang,
        events: {
            "submit": 'doSignIn'
        },
        initialize: function () {
            var self = this;
            APP.Sandbox.eventSubscribe('global:auth:status:active', function(){
                self.remove();
            });
            _.bindAll(this, 'render');
        },
        doSignIn: function () {
            var authData = this.collectCredentials();
            Auth.signin(authData.email, authData.password, authData.remember, this.render);
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
        render: function (auth_id, response) {
            this.extras = response;
            var data = Utils.getHBSTemplateData(this);
            this.$el.html(this.template(data));
            return this;
        }
    });

    return SignIn;

});