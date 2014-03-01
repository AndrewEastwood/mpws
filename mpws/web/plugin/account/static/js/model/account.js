define("plugin/account/js/model/account", [
    'default/js/lib/sandbox',
    'cmn_jquery',
    'default/js/lib/underscore',
    'default/js/model/mModel',
    'default/js/lib/cache'
], function (Sandbox, $, _, MModel, Cache) {

    var Model = MModel.getNew();

    var Account = Model.extend({
        source: 'account',
        fn: 'profile',
        initialize: function () {
            // debugger;
            this.getStatus();

            // Sandbox.eventSubscribe('account:signout', _.bind(this.doLogout, this));
            Sandbox.eventSubscribe('account:status', _.bind(this.getStatus, this));
        },
        getStatus: function (options) {
            var self = this;
            this.updateUrl({
                action: 'status'
            });
            $.post(this.url, function (responce) {
                var _data = self.extractModelDataFromRespce(responce);
                Cache.setObject('AccountProfileID', _data.profile && _data.profile.ID);
                self.set(_data);
                self.trigger('change');
                Sandbox.eventNotify('account:status:received', _data);
            });
        },
        doLogin: function (data) {
            // debugger;
            var self = this;
            this.updateUrl({
                action: 'signin'
            });
            $.post(this.url, {credentials: data}, function (responce) {
                var _data = self.extractModelDataFromRespce(responce);
                Cache.setObject('AccountProfileID', _data.profile && _data.profile.ID);
                self.set(_data);
                self.trigger('change');
                Sandbox.eventNotify('account:signed:in', _data);
            });
        },
        doLogout: function () {
            // debugger;
            var self = this;
            this.updateUrl({
                action: 'signout'
            });
            $.post(this.url, function (responce) {
                // var _data = self.extractModelDataFromRespce(responce);
                Cache.setObject('AccountProfileID', null);
                self.clear();
                self.trigger('change');
                Sandbox.eventNotify('account:signed:out', null);
            });
        },
        showProfile: function () {

        },
        editProfile: function () {

        },

    });

    // we have only one instance
    return new Account();

});