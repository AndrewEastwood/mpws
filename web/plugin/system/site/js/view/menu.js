define("plugin/system/site/js/view/menu", [
    'default/js/lib/sandbox',
    'plugin/system/site/js/view/menuUser',
    'plugin/system/site/js/view/menuSignUp',
], function (Sandbox, MenuUser, MenuSignUp) {

    return function (models) {
        // create SignUp button
        var menuSignUp = new MenuSignUp({
            model: models.user
        });
        menuSignUp.render();

        // create SignIn button
        var menuUser = new MenuUser({
            model: models.user
        });
        menuUser.render();

        Sandbox.eventSubscribe('global:loader:complete', function () {
            // placeholders.common.menu
            Sandbox.eventNotify('global:content:render', [
                {
                    name: 'CommonMenuRight',
                    el: menuSignUp.$el,
                    append: true
                },
                {
                    name: 'CommonMenuRight',
                    el: menuUser.$el,
                    append: true
                }
            ]);
        });
    }

});