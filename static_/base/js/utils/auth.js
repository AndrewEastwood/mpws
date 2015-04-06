define([
    "jquery",
    "underscore",
    'backbone',
    "cachejs"
], function ($, _, Backbone, Cache) {

    var authKey = APP.config.AUTHKEY;

    var Auth = _.extend({
        verifyStatus: function () {
            // debugger
            var user = Auth.getUserID();
            Backbone.trigger('auth:info', user);
            if (Auth.user === user) {
                // APP.Sandbox.eventNotify("global:auth:status:unchanged", user);
                return;
            }
            Auth.user = user;
            if (Auth.user) {
                // Backbone.trigger('auth:registered', user);
                this.trigger('registered');
                // APP.Sandbox.eventNotify("global:auth:status:active");
            } else {
                // Backbone.trigger('auth:guest', user);
                this.trigger('guest');
                // APP.Sandbox.eventNotify("global:auth:status:inactive");
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
            var that = this;
            var query = {
                fn: 'signin'
            };
            return $.post(APP.getAuthLink(query), {
                email: email,
                password: password,
                remember: remember,
            }, function (response) {
                that.trigger('signin:ok');
                if (_.isFunction(callback))
                    callback(Auth.getUserID(), response);
            });
        },
        signout: function (callback) {
            var that = this;
            var query = {
                fn: 'signout'
            };
            return $.ajax({
                url: APP.getAuthLink(query),
                type: 'DELETE',
                success: function (response) {
                    that.trigger('signout:ok');
                    if (_.isFunction(callback))
                        callback(Auth.getUserID(), response);
                }
            });
        }
    }, Backbone.Events);

    // init user data
    Auth.user = Auth.getUserID()

    Backbone.on("global:ajax:response", function (/*data*/) {
        Auth.verifyStatus();
        // if (!data.isAuthRequest) {
        // }
    });

    return Auth;

});