define("default/js/lib/auth", [
    "default/js/lib/sandbox",
    "cmn_jquery",
    "default/js/lib/underscore"
], function (Sandbox, $, _) {

    var Auth = {
        getStatus: function () {
            var url = APP.getApiLink({
                source: 'account',
                fn: 'status'
            });
            return $.get(url, function (response) {
                Cache.setObject('Account', response.profile);
                Sandbox.eventNotify('plugin:account:status:received', response.profile);
            }).error(function(){
                Sandbox.eventNotify('plugin:account:status:received', null);
            });
        },
        signin: function (email, password) {
            // debugger;
            var url = APP.getApiLink({
                source: 'account',
                fn: 'signin'
            });
            return $.post(url, {
                email: email,
                password: password
            }, function (response) {
                Cache.setObject('Account', response.profile);
                if (response.profile)
                    Sandbox.eventNotify('plugin:account:signed:in', response.profile);
            }).error(function(){
                debugger;
                Sandbox.eventNotify('plugin:account:signed:in', false);
            });
        },
        signout: function () {
            // debugger;
            var url = APP.getApiLink({
                source: 'account',
                fn: 'signout'
            });
            return $.post(url, function () {
                Cache.setObject('Account', null);
                Sandbox.eventNotify('plugin:account:signed:out', null);
            }).error(function(){
                debugger;
                Sandbox.eventNotify('plugin:account:signed:out', false);
            });
        }
    };

    return Auth;

});