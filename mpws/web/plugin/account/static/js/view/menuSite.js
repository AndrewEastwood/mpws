define("plugin/account/js/view/menuSite", [
    'customer/js/site',
    'plugin/account/js/view/menuSignIn',
    'plugin/account/js/view/menuSignUp',
], function (Site, MenuSignIn, MenuSignUp) {

    // create SignIn button
    var menuSignUp = new MenuSignUp();
    menuSignUp.fetchAndRender();

    // create SignIn button
    var menuSignIn = new MenuSignIn();
    menuSignIn.fetchAndRender();

    return {
        render: function () {
            Site.addMenuItemRight(menuSignUp.$el);
            Site.addMenuItemRight(menuSignIn.$el);
        }
    }

});