requirejs.config({
    //Remember: only use shim config for non-AMD scripts,
    //scripts that do not already call define(). The shim
    //config will not work correctly if used on AMD scripts,
    //in particular, the exports and init config will not
    //be triggered, and the deps config will be confusing
    //for those cases.
    shim: {
        'customer/js/lib/sb-admin-2': {
            //These script dependencies should be loaded before loading
            //customer/js/lib/sb-admin-2.js
            deps: ['cmn_jquery'],
            //module value.
            exports: 'customer/js/lib/sb-admin-2'
        },
        'customer/js/lib/metisMenu': {
            deps: ['cmn_jquery'],
            exports: 'customer/js/lib/metisMenu'
        }
    }
});

define("customer/js/router", [
    'default/js/lib/sandbox',
    'default/js/lib/auth',
    'default/js/lib/cache',
    'default/js/lib/bootstrap',
    'customer/js/lib/sb-admin-2',
    'customer/js/lib/metisMenu'
], function (Sandbox, Auth, Cache) {

    $('head title').text(APP.config.URL_PUBLIC_TITLE);
    $('a.navbar-brand').attr('href', APP.config.URL_PUBLIC_HOMEPAGE).html(APP.config.URL_PUBLIC_TITLE);

    if (!APP.hasPlugin('account')) {
        throw "Account plugin is unavailable";
    }

    var _ifNotAuthorizedNavigateToSignin = function () {
        // debugger;
        if (!Auth.getAccountID()) {
            APP.xhrAbortAll();
            if (!/!\/signin/.test(Backbone.history.getHash())) {
                Backbone.history.fragment = false;
                window.location.href = '/#!/signin';
                // Backbone.history.navigate('signin', true);
                window.location.reload();
            }
        }
    }

    Sandbox.eventSubscribe('global:page:signout', function () {
        _ifNotAuthorizedNavigateToSignin();
    });

    Sandbox.eventSubscribe('global:page:signin', function () {
        if (Auth.getAccountID()) {
            Backbone.history.navigate("", true);
        }
    });

    Sandbox.eventSubscribe('global:route', function () {
        _ifNotAuthorizedNavigateToSignin();
    });

    Sandbox.eventSubscribe('global:auth:status:active', function (data) {
        Backbone.history.navigate(Cache.getFromLocalStorage('location') || "", true);
    });

    Sandbox.eventSubscribe('global:auth:status:inactive', function () {
        _ifNotAuthorizedNavigateToSignin();
    });

    Sandbox.eventSubscribe('customer:menu:set', function (renderParams) {
        Sandbox.eventNotify("global:content:render", renderParams);
        $('#side-menu').metisMenu();
    });

    // function CustomerClass () {}

    // CustomerClass.waitPlugins = true;

    // return CustomerClass;

});