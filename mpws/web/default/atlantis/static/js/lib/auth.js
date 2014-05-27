define("default/js/lib/auth", [
    "default/js/lib/sandbox",
    "cmn_jquery",
    "default/js/lib/underscore",
    "default/js/lib/cache"
], function (Sandbox, $, _, Cache) {

    var Auth = {
        getAccount: function () {
            return Cache.getCookie('user') || false;
        },
        getStatus: function () {
            return $.get(APP.getAuthLink(), function (response) {
                Cache.setCookie('user', response);
                Sandbox.eventNotify('golobal:auth:status:received', response);
            }).error(function(){
                Sandbox.eventNotify('golobal:auth:status:received', null);
            });
        },
        signin: function (email, password, remember) {
            return $.post(APP.getAuthLink(), {
                email: email,
                password: password,
                remember: remember,
            }, function (response) {
                Cache.setCookie('user', response);
                if (response)
                    Sandbox.eventNotify('golobal:auth:signed:in', response);
            }).error(function(){
                // debugger;
                Sandbox.eventNotify('golobal:auth:signed:in', false);
            });
        },
        signout: function () {
            return $.post(APP.getAuthLink(), function () {
                Cache.setCookie('user', null);
                Sandbox.eventNotify('golobal:auth:signed:out', null);
            }).error(function(){
                // debugger;
                Sandbox.eventNotify('golobal:auth:signed:out', false);
            });
        }
    };

    return Auth;

});