define("default/js/lib/auth", [
    "default/js/lib/sandbox",
    "cmn_jquery",
    "default/js/lib/underscore",
    "default/js/lib/cache"
], function (Sandbox, $, _, Cache) {

    var Auth = {};
    // Sandbox.eventNotify("global:ajax:responce", responseObj);
    Sandbox.eventSubscribe("global:ajax:responce", function(responseObj) {
        // debugger;
        if (APP.config.ISTOOLBOX && responseObj) {
            if(responseObj.authenticated && Backbone.history.fragment === "signin") {
                Backbone.history.navigate(Cache.getFromLocalStorage("location") || '', true);
            } else if (!responseObj.authenticated) {
                $.xhrPool.abortAll();
                // APP.commonElements.empty();
                // Sandbox.eventNotify("global:session:needlogin", responseObj.error);
                if (Backbone.history.fragment !== "signin") {
                    Backbone.history.navigate('signin', true);
                    window.location.reload();
                }
            }
        }
        Auth.setStatus(responseObj && responseObj.authenticated);
    });

    Auth = {
        isAuthenticated: false,
        setStatus: function (status) {
            Auth.isAuthenticated = !!status;
            if (Auth.isAuthenticated)
                Sandbox.eventNotify("global:auth:status:active");
            else
                Sandbox.eventNotify("global:auth:status:inactive");
        },
        getAccount: function () {
            return Cache.getCookie('user') || false;
        },
        getStatus: function () {
            var query = {
                fn: 'status'
            };
            return $.get(APP.getAuthLink(query), function (response) {
                Cache.setCookie('user', response);
                Sandbox.eventNotify('global:auth:status:received', response);
            }).error(function(){
                Sandbox.eventNotify('global:auth:status:received', null);
            }).always(function(response){
                Auth.isAuthenticated = response && response.authenticated;
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
            }, function (response) {
                Cache.setCookie('user', response);
                if (response)
                    Sandbox.eventNotify('global:auth:signed:in', response);
            }).error(function(){
                // debugger;
                Sandbox.eventNotify('global:auth:signed:in', false);
            }).always(function(response){
                Auth.isAuthenticated = response && response.authenticated;
            });
        },
        signout: function () {
            var query = {
                fn: 'signout'
            };
            return $.post(APP.getAuthLink(query), function () {
                Cache.setCookie('user', null);
                Sandbox.eventNotify('global:auth:signed:out', null);
            }).error(function(){
                // debugger;
                Sandbox.eventNotify('global:auth:signed:out', false);
            }).always(function(response){
                Auth.isAuthenticated = response && response.authenticated;
            });
        }
    };

    return Auth;

});