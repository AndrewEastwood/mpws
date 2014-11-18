define("plugin/account/toolbox/js/view/signin", [
    'default/js/lib/sandbox',
    'cmn_jquery',
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'default/js/lib/auth',
    'default/js/lib/utils',
    'default/js/plugin/hbs!plugin/account/toolbox/hbs/signin',
    /* lang */
    'default/js/plugin/i18n!plugin/account/toolbox/nls/translation'
], function (Sandbox, $, _, Backbone, Auth, Utils, tpl, lang) {

    var SignIn = Backbone.View.extend({
        tagName: 'form',
        className: 'form form-horizontal toolbox-component-form-signin',
        template: tpl,
        lang: lang,
        events: {
            "submit": 'doSignIn'
        },
        initialize: function () {
            var self = this;
            Sandbox.eventSubscribe('global:auth:status:active', function(){
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
            this.$el.html(tpl(data));
            return this;
        }
    });

    return SignIn;

});