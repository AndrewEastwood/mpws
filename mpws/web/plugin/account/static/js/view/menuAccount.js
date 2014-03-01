define("plugin/account/js/view/menuAccount", [
    'default/js/lib/sandbox',
    'default/js/view/mView',
    'plugin/account/js/model/account',
    'default/js/plugin/hbs!plugin/account/hbs/menuAccount',
    /* lang */
    'default/js/plugin/i18n!plugin/account/nls/account'
], function (Sandbox, MView, ModelAccountInstance, tpl, lang) {

    var MenuAccount = MView.extend({
        viewName: 'AccountMenu',
        tagName: 'li',
        className: 'dropdown account-dropdown-signin',
        id: 'account-dropdown-signin-ID',
        template: tpl,
        model: ModelAccountInstance,
        lang: lang,
        events: {
            "submit .form": 'doSignIn',
            "click #accountProfileSignOutID": 'doSignOut',
        },
        initialize: function () {
            MView.prototype.initialize.call(this);
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
        },
        addMenuItem: function (item) {
            if (item instanceof $) {
                if (item.is('li'))
                    this.getProfileMenu().append(item);
                else
                    this.getProfileMenu().append($('<li>').append(item));
            } else if (typeof item === "string") {
                this.getProfileMenu().append($('<li>').text(item));
            } else if (Array.isArray(item)) {
                for (var key in item)
                    this.addMenuItem(item[key]);
            }
        },
        getProfileMenu: function () {
            if (this.model.has('profile'))
                return this.$('.plugin-account-menu');
            return false;
        }
    });

    return MenuAccount;

});