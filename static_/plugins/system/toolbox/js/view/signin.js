define([
    'jquery',
    'underscore',
    'handlebars',
    'backbone',
    'auth',
    'utils',
    'text!plugins/system/toolbox/hbs/signin.hbs',
    'imageInsert',
    /* lang */
    'i18n!plugins/system/toolbox/nls/translation'
], function ($, _, Handlebars, Backbone, Auth, Utils, tpl, lang) {

    var SignIn = Backbone.View.extend({
        tagName: 'form',
        className: 'form form-horizontal toolbox-component-form-signin',
        template: Handlebars.compile(tpl), // check
        lang: lang,
        events: {
            'submit': 'doSignIn'
        },
        initialize: function () {
            var that = this;
            Auth.on('registered', function(){
                that.remove();
                location.reload();
            });
            _.bindAll(this, 'render');
        },
        doSignIn: function () {
            var authData = this.collectCredentials();
            Auth.signin(authData.email, authData.password, authData.remember, this.render);
            return false;
        },
        collectCredentials: function () {
            var that = this;
            return {
                email: that.$('#signinEmail').val(),
                password: that.$('#signinPassword').val(),
                remember: that.$('#signinRemember').is(':checked')
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