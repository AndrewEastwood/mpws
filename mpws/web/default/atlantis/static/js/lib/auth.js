define("default/js/lib/auth", [
    "default/js/lib/sandbox",
    "cmn_jquery",
    "default/js/lib/underscore",
    "default/js/lib/cache"
], function (Sandbox, $, _, Cache) {

    var Auth = {};

    Sandbox.eventSubscribe("global:ajax:responce", function(response) {
        Auth.setStatus(response);
    });

    Auth = {
        setStatus: function (response) {
            // debugger;
            var auth_id = response && response.auth_id;
            if (Auth.auth_id === auth_id)
                return;

            Auth.auth_id = auth_id;
            Cache.setCookie('auth_id', auth_id);

            if (Auth.auth_id === null)
                Sandbox.eventNotify("global:auth:status:inactive");
            else
                Sandbox.eventNotify("global:auth:status:active");
        },
        getAccountID: function () {
            return Cache.getCookie('auth_id') || null;
        },
        getStatus: function () {
            var query = {
                fn: 'status'
            };
            return $.get(APP.getAuthLink(query));
        },
        signin: function (email, password, remember) {
            var query = {
                fn: 'signin'
            };
            return $.post(APP.getAuthLink(query), {
                email: email,
                password: password,
                remember: remember,
            });

        },
        signout: function () {
            var query = {
                fn: 'signout'
            };
            return $.post(APP.getAuthLink(query));
        }
    };

    return Auth;

});