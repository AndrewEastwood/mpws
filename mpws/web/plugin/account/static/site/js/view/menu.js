define("plugin/account/site/js/view/menu", [
    'default/js/lib/sandbox',
    'plugin/account/site/js/view/menuAccount',
    'plugin/account/site/js/view/menuSignUp',
], function (Sandbox, MenuAccount, MenuSignUp) {

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