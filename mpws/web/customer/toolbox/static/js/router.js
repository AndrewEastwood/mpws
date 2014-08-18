define("customer/js/router", [
    'default/js/lib/sandbox',
    'customer/js/view/pageContainer',
    'default/js/lib/auth',
    'default/js/lib/cache'
], function (Sandbox, PageContainer, Auth, Cache) {

    if (!APP.hasPlugin('account')) {
        throw "Account plugin is unavailable";
    }

    var _ifNotAuthorizedNavigateToSignin = function () {
        // debugger;
        if (!Auth.getAccountID()) {
            APP.xhrAbortAll();
            if (!/signin/.test(Backbone.history.getHash())) {
                Backbone.history.fragment = false;
                Backbone.history.navigate('signin', true);
                // window.location.reload();
            }
        }
    }

    Sandbox.eventSubscribe('global:page:signout', function () {
        _ifNotAuthorizedNavigateToSignin();
    });

    Sandbox.eventSubscribe('global:route', function () {
        _ifNotAuthorizedNavigateToSignin();
    });

    Sandbox.eventSubscribe('global:auth:status:active', function (data) {
        var pageContainer = new PageContainer();
        pageContainer.render();
        Backbone.history.navigate(Cache.getFromLocalStorage('location') || "", true);
    });

    Sandbox.eventSubscribe('global:auth:status:inactive', function () {
        // debugger;
        _ifNotAuthorizedNavigateToSignin();
    });

});