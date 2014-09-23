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


        // events: {
        //     "submit": 'doSignIn'
        // },
        doSignIn: function () {
            var authData = this.collectCredentials();
            Auth.signin(authData.email, authData.password, authData.remember);
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
        },




        initialize: function () {
            this.listenTo(this.model, "change", this.render);
        },
        // render: function () {
        //     this.$el.html(this.template(Utils.getHBSTemplateData(this)));
        // },
        // doSignIn: function () {
        //     this.model.doLogin(this.collectCredentials());
        //     return false;
        // },

        // collectCredentials: function () {
        //     var self = this;
        //     return {
        //         email: self.$('#signinEmail').val(),
        //         password: self.$('#signinPassword').val(),
        //         remember: self.$('#signinRemember').is(':checked')
        //     }
        // },
        // addMenuItem: function (item) {
        //     if (item instanceof $) {
        //         if (item.is('li'))
        //             this.getProfileMenu().append(item);
        //         else
        //             this.getProfileMenu().append($('<li>').append(item));
        //     } else if (typeof item === "string") {
        //         this.getProfileMenu().append($('<li>').text(item));
        //     } else if (Array.isArray(item)) {
        //         for (var key in item)
        //             this.addMenuItem(item[key]);
        //     }
        // },
        // getProfileMenu: function () {
        //     if (this.model.has('profile'))
        //         return this.$('.plugin-account-menu');
        //     return false;
        // }
    });

    return MenuAccount;

});