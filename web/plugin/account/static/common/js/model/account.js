define("plugin/account/common/js/model/account", [
    'plugin/account/common/js/model/accountAddress',
    'default/js/lib/sandbox',
    'cmn_jquery',
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'default/js/lib/cache'
], function (AccountAddress, Sandbox, $, _, Backbone, Cache) {

    var Account = Backbone.Model.extend({
        idAttribute: "ID",
        url: function () {
            var _params =  {
                source: 'account',
                fn: 'user'
            };
            if (!this.isNew())
                _params.id = this.id;
            return APP.getApiLink(_params);
        },
        initialize: function () {
            // autoclean errors when user goes wherever else
            Sandbox.eventSubscribe('global:route', $.proxy(function () {
                this.unset('errors', {silent: true});
                this.unset('success', {silent: true});
            }, this));
        },
        changePassword: function (password, confirmation) {
            var data = {
                Password: password,
                ConfirmPassword: confirmation
            };
            this.save(data, {patch: true});
        },
        isSaved: function () {
            return this.get('success');
        }

    });

    return Account;

});