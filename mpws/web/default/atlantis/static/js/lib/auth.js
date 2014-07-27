define("default/js/lib/auth", [
    "default/js/lib/sandbox",
    "cmn_jquery",
    "default/js/lib/underscore",
    "default/js/lib/cache"
], function (Sandbox, $, _, Cache) {

    var Auth = {};

    Sandbox.eventSubscribe("global:ajax:responce", function(response) {
        if (APP.config.ISTOOLBOX && response) {
            if(response.authenticated && Backbone.history.fragment === "signin") {
                Backbone.history.navigate(Cache.getFromLocalStorage("location") || '', true);
            } else if (!response.authenticated) {
                $.xhrPool.abortAll();
                if (Backbone.history.fragment !== "signin") {
                    Backbone.history.navigate('signin', true);
                    window.location.reload();
                }
            }
        }
        Auth.setStatus(response);
    });

    Auth = {
        setStatus: function (response) {
            // debugger;
            var status = response && response.authenticated;
            if (Auth.isAuthenticated === status)
                return;

            Auth.isAuthenticated = status;

            if (Auth.isAuthenticated)
                Sandbox.eventNotify("global:auth:status:active");
            else
                Sandbox.eventNotify("global:auth:status:inactive");
        },
        getAccountID: function () {
            return Cache.getCookie('auth_id') || false;
        },
        getStatus: function () {
            var query = {
                fn: 'status'
            };
            return $.get(APP.getAuthLink(query), function(response){
                // debugger;
                // Sandbox.eventNotify('global:auth:status:received', response);
                Auth.setStatus(response, true);
                Cache.setCookie('auth_id', response.auth_id || null);
            });
        },
        signin: function (email, password, remember) {
            var query = {
                fn: 'signin'
            };
            return $.post(APP.getAuthLink(query), {
                email: email,
                password: password,
                remember: remember,
            }, function(response){
                // debugger;
                // Sandbox.eventNotify('global:auth:status:received', response);
                Cache.setCookie('auth_id', response.auth_id || null);
            });

        },
        signout: function () {
            var query = {
                fn: 'signout'
            };
            return $.post(APP.getAuthLink(query), function () {
                Cache.setCookie('auth_id', null);
            });
        }
    };

    return Auth;

});