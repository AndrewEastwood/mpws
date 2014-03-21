define("plugin/account/js/view/site/menu", [
    'default/js/lib/sandbox',
    'customer/js/site',
    'plugin/account/js/view/site/menuAccount',
    'plugin/account/js/view/site/menuSignUp',
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

    Sandbox.eventSubscribe('global:loader:complete', function () {
        Sandbox.eventNotify('global:content:render', [
            {
                name: 'CommonMenuRight',
                el: menuSignUp.$el,
                append: true
            },
            {
                name: 'CommonMenuRight',
                el: menuAccount.$el,
                append: true
            }
        ]);
    });

});