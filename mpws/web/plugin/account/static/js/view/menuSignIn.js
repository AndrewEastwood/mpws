define("plugin/account/js/view/menuSignIn", [
    'default/js/view/mView',
    'default/js/plugin/hbs!plugin/account/hbs/menuSignIn'
], function (MView, tpl) {

    var MenuSignIn = MView.extend({
        tagName: 'li',
        className: 'dropdown account-dropdown-signin',
        id: 'account-dropdown-signin-ID',
        template: tpl
    });

    return MenuSignIn;

});