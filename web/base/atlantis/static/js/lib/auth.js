define("default/js/lib/auth", [
    "default/js/lib/sandbox",
    "cmn_jquery",
    "default/js/lib/underscore",
    'default/js/lib/backbone',
    "default/js/lib/cache"
], function (Sandbox, $, _, Backbone, Cache) {

    var Auth = _.extend({
        verifyStatus: function () {
            // debugger;
            var auth_id = Auth.getUserID();
            if (Auth.auth_id === auth_id) {
                return;
            }
            Auth.auth_id = auth_id;
            if (Auth.auth_id) {
                this.trigger('registered');
                Sandbox.eventNotify("global:auth:status:active");
            } else {
                this.trigger('guest');
                Sandbox.eventNotify("global:auth:status:inactive");
            }
        },
        getUserID: function () {
            return Cache.getCookie('auth_id') || null;
        },
        getStatus: function (callback) {
            var query = {
                fn: 'status'
            };
            return $.get(APP.getAuthLink(query), function (response) {
                if (_.isFunction(callback))
                    callback(Auth.getUserID(), response);
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
                    callback(Auth.getUserID(), response);
            });
        },
        signout: function (callback) {
            var query = {
                fn: 'signout'
            };
            return $.post(APP.getAuthLink(query), function (response) {
                if (_.isFunction(callback))
                    callback(Auth.getUserID(), response);
            });
        }
    }, Backbone.Events);

    Sandbox.eventSubscribe("global:ajax:responce", function () {
        Auth.verifyStatus();
    });

    return Auth;

});