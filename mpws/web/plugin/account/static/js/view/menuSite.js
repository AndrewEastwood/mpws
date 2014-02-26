define("plugin/account/js/view/menuSite", [
    'customer/js/site',
    'plugin/account/js/view/menuAccount',
], function (Site, MenuAccount) {

    // create account button
    var menuAccount = new MenuAccount();
    menuAccount.fetchAndRender();

    return {
        render: function () {
            Site.addMenuItemRight(menuAccount.$el);
        }
    }

});