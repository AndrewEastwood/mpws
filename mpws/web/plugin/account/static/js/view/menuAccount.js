define("plugin/account/js/view/menuAccount", [
    'default/js/view/mView',
    'default/js/plugin/hbs!plugin/account/hbs/menuAccount'
], function (MView, tpl) {

    var MenuAccount = MView.extend({
        tagName: 'li',
        className: 'dropdown account-dropdown-signin',
        id: 'account-dropdown-signin-ID',
        template: tpl
    });

    return MenuAccount;

});