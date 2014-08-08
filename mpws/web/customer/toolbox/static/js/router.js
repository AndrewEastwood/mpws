define("customer/js/router", [
    'default/js/lib/sandbox',
    'customer/js/view/menu',
    'default/js/lib/auth',
], function (Sandbox, Menu, Auth) {

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



    var _ifNotAuthorizedNavigateTo = function () {
        if (Auth.getAccountID() && Backbone.history.fragment === "signin") {
            Backbone.history.navigate(Cache.getFromLocalStorage("location") || '', true);
            return true;
        } else if (!Auth.getAccountID()) {
            $.xhrPool.abortAll();
            if (Backbone.history.fragment !== "signin") {
                Backbone.history.navigate('signin', true);
                window.location.reload();
            }
            return false;
        }

        // if (!Auth.getAccountID() && /^account/.test(Backbone.history.fragment)) {
        //     Backbone.history.navigate("signin", true);
        //     return true;
        // }
        // return false;
    }

    Sandbox.eventSubscribe('global:route', function () {
        _ifNotAuthorizedNavigateTo('signin');
        var authAccountID = Auth.getAccountID();
        if (authAccountID) {
            account.set('ID', authAccountID);
            account.fetch();
        }
    });

    Sandbox.eventSubscribe('global:auth:status:active', function (data) {
    //     var authAccountID = Auth.getAccountID();
    //     if (authAccountID) {
    //         account.set('ID', authAccountID);
    //         account.fetch();
    //     }
        var menu = new Menu();
        menu.render();
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