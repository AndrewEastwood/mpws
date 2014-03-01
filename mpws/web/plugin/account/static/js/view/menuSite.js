define("plugin/account/js/view/menuSite", [
    'default/js/lib/sandbox',
    'customer/js/site',
    'plugin/account/js/view/menuAccount',
    'plugin/account/js/view/menuSignUp',
], function (Sandbox, Site, MenuAccount, MenuSignUp) {

    // create SignIn button
    var menuSignUp = new MenuSignUp();
    menuSignUp.fetchAndRender();

    // create SignIn button
    var menuAccount = new MenuAccount();
    menuAccount.fetchAndRender();

    Sandbox.eventSubscribe('view:AccountMenu', function (view) {
        if (!view.model.has('profile')) { 
            menuSignUp.$el.removeClass('hidden');
            return;
        }

        menuSignUp.$el.addClass('hidden');
    });

    return {
        render: function () {
            Site.addMenuItemRight(menuSignUp.$el);
            Site.addMenuItemRight(menuAccount.$el);
        }
    }

});