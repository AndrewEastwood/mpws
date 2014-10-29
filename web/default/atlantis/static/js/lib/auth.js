define("default/js/lib/auth", [
    "default/js/lib/sandbox",
    "cmn_jquery",
    "default/js/lib/underscore",
    "default/js/lib/cache"
], function (Sandbox, $, _, Cache) {

    var Auth = {};

    Sandbox.eventSubscribe("global:ajax:responce", function (response) {
        Auth.verifyStatus(response);
    });

    Auth = {
        verifyStatus: function () {
            // debugger;
            var auth_id = Auth.getAccountID();
            if (Auth.auth_id === auth_id) {
                return;
            }
            Auth.auth_id = auth_id;
            if (Auth.auth_id) {
                Sandbox.eventNotify("global:auth:status:active");
            } else {
                Sandbox.eventNotify("global:auth:status:inactive");
            }
        },
        getAccountID: function () {
            return Cache.getCookie('auth_id') || null;
        },
        getStatus: function (callback) {
            var query = {
                fn: 'status'
            };
            return $.get(APP.getAuthLink(query), function (response) {
                if (_.isFunction(callback))
                    callback(Auth.getAccountID(), response);
            });
        },
        signin: function (email, password, remember, callback) {
            var query = {
                fn: 'signin'
            };
            return $.post(APP.getAuthLink(query), {
                email: email,
                password: password,
                remember: remember,
            }, function (response) {
                if (_.isFunction(callback))
                    callback(Auth.getAccountID(), response);
            });
        },
        signout: function (callback) {
            var query = {
                fn: 'signout'
            };
            return $.post(APP.getAuthLink(query), function (response) {
                if (_.isFunction(callback))
                    callback(Auth.getAccountID(), response);
            });
        }
    };

    return Auth;

});