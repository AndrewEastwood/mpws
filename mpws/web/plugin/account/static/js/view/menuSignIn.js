define("plugin/account/js/view/menuSignIn", [
    'default/js/lib/sandbox',
    'default/js/view/mView',
    'default/js/plugin/hbs!plugin/account/hbs/menuSignIn'
], function (Sandbox, MView, tpl) {

    var MenuSignIn = MView.extend({
        tagName: 'li',
        className: 'dropdown account-dropdown-signin',
        id: 'account-dropdown-signin-ID',
        template: tpl,
        events: {
            'click .btn-signin': 'doSignIn'
        },
        doSignIn: function () {
            Sandbox.eventNotify("account:signin", this.collectCredentials());
        },
        collectCredentials: function () {
            var self = this;
            return {
                login: self.$('#signinEmail').val(),
                password: self.$('#signinPassword').val(),
            }
        }
    });

    return MenuSignIn;

});