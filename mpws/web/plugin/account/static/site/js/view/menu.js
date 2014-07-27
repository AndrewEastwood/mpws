define("plugin/account/site/js/view/menu", [
    'default/js/lib/sandbox',
    'plugin/account/site/js/view/menuAccount',
    'plugin/account/site/js/view/menuSignUp',
], function (Sandbox, MenuAccount, MenuSignUp) {

    return function (models) {
        // create SignIn button
        var menuSignUp = new MenuSignUp({
            model: models.account
        });
        menuSignUp.render();

        // create SignIn button
        var menuAccount = new MenuAccount({
            model: models.account
        });
        menuAccount.render();

        // Sandbox.eventSubscribe('view:AccountMenu', function (view) {
        //     if (!view.model.has('profile')) {
        //         menuSignUp.$el.removeClass('hidden');
        //         return;
        //     }
        //     menuSignUp.$el.addClass('hidden');
        // });

        // Sandbox.eventSubscribe('global:loader:complete', function () {
        //     Sandbox.eventNotify('global:content:render', [
        //         {
        //             name: 'CommonMenuRight',
        //             el: menuSignUp.$el,
        //             append: true
        //         },
        //         {
        //             name: 'CommonMenuRight',
        //             el: menuAccount.$el,
        //             append: true
        //         }
        //     ]);
        // });

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
                    el: menuAccount.$el,
                    append: true
                }
            ]);
        });
    }

});