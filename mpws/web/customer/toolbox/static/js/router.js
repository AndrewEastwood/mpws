define("customer/js/router", [
    'default/js/lib/sandbox',
    'customer/js/view/pageContainer',
    'default/js/lib/auth',
    'default/js/lib/cache',
], function (Sandbox, PageContainer, Auth, Cache) {

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
            APP.xhrAbortAll();
            Backbone.history.navigate(route, true);
            window.location.reload();
        }
    }

    Sandbox.eventSubscribe('global:auth:status:active', function (data) {
        var pageContainer = new PageContainer();
        pageContainer.render();
        Backbone.history.navigate(Cache.getFromLocalStorage('location') || "", true);
    });

    // Sandbox.eventSubscribe('global:auth:status:inactive', function () {
    //     // debugger;
    //     _ifNotAuthorizedNavigateTo('signin');
    // });

});