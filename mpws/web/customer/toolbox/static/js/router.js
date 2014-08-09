define("customer/js/router", [
    'default/js/lib/sandbox',
    'customer/js/view/menu',
    'default/js/lib/auth',
    'default/js/lib/cache',
], function (Sandbox, Menu, Auth, Cache) {

    // // with every route we get user status
    // Sandbox.eventSubscribe('global:route', function () {
    //     Auth.getStatus();
    // });
        // if (APP.config.ISTOOLBOX && response) {
        //     if(response.auth_id && Backbone.history.fragment === "signin") {
        //         Backbone.history.navigate(Cache.getFromLocalStorage("location") || '', true);
        //     } else if (!response.auth_id) {
        //         $.xhrPool.abortAll();
        //         if (Backbone.history.fragment !== "signin") {
        //             Backbone.history.navigate('signin', true);
        //             window.location.reload();
        //         }
        //     }
        // }
    if (!APP.hasPlugin('account')) {
        throw "Account plugin is unavailable";
    }

    Sandbox.eventSubscribe('global:page:signout', function (fragment) {
        _ifNotAuthorizedNavigateTo(fragment, 'signin');
    });

    Sandbox.eventSubscribe('global:route', function (fragment) {
        _ifNotAuthorizedNavigateTo(fragment, 'signin');
    });

    var _ifNotAuthorizedNavigateTo = function (fragment, route) {
        // debugger;
        if (!Auth.getAccountID() && fragment !== route) {
            $.xhrPool.abortAll();
            Backbone.history.navigate(route, true);
            window.location.reload();
        }
    }


    Sandbox.eventSubscribe('global:auth:status:active', function (data) {
    //     var authAccountID = Auth.getAccountID();
    //     if (authAccountID) {
    //         account.set('ID', authAccountID);
    //         account.fetch();
    //     }
        var menu = new Menu();
        menu.render();
        Backbone.history.navigate('', true);
    });

    // Sandbox.eventSubscribe('global:auth:status:inactive', function (data) {
    //     account.clear();
    //     _navigateToHomeIfNotAuthorizedFn();
    // });


    // // when page is loaded first time
    // Sandbox.eventSubscribe('global:loader:complete', function (options) {
    //     Sandbox.eventNotify('global:page:setTitle', 'Toolbox');
    // });

    // show menu when user is active
    // Sandbox.eventSubscribe('global:auth:status:active', function (options) {
    //     // debugger;
    //     var menu = new Menu();
    //     menu.render();
    // });
});