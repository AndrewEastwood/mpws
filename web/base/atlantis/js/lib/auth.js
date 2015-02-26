define("default/js/lib/auth", [
    "default/js/lib/sandbox",
    "jquery",
    "default/js/lib/underscore",
    'default/js/lib/backbone',
    "default/js/lib/cache"
], function (Sandbox, $, _, Backbone, Cache) {

    var authKey = APP.config.AUTHKEY;

    var Auth = _.extend({
        verifyStatus: function () {
            var user = Auth.getUserID();
            Backbone.trigger('auth:info', user);
            if (Auth.user === user) {
                // Sandbox.eventNotify("global:auth:status:unchanged", user);
                return;
            }
            Auth.user = user;
            if (Auth.user) {
                // Backbone.trigger('auth:registered', user);
                this.trigger('registered');
                Sandbox.eventNotify("global:auth:status:active");
            } else {
                // Backbone.trigger('auth:guest', user);
                this.trigger('guest');
                Sandbox.eventNotify("global:auth:status:inactive");
            }
        },
        getUserID: function () {
            return Cache.getCookie(authKey) || null;
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
            return $.ajax({
                url: APP.getAuthLink(query),
                type: 'DELETE',
                success: function (response) {
                    if (_.isFunction(callback))
                        callback(Auth.getUserID(), response);
                }
            });
        }
    }, Backbone.Events);

    Sandbox.eventSubscribe("global:ajax:responce", function () {
        Auth.verifyStatus();
    });

    return Auth;

});