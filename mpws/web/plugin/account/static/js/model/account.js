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
            var url = this.getUrl({
                action: 'status'
            });
            $.post(url, function (responce) {
                var _data = self.extractModelDataFromRespce(responce);
                Cache.setObject('AccountProfileID', _data.profile && _data.profile.ID);
                self.set(_data);
                self.trigger('change');
                Sandbox.eventNotify('account:status:received', _data.profile);
            });
        },
        doLogin: function (data) {
            // debugger;
            var self = this;
            var url = this.getUrl({
                action: 'signin'
            });
            $.post(url, {credentials: data}, function (responce) {
                var _data = self.extractModelDataFromRespce(responce);
                Cache.setObject('AccountProfileID', _data.profile && _data.profile.ID);
                self.set(_data);
                self.trigger('change');
                Sandbox.eventNotify('account:signed:in', _data.profile);
            });
        },
        doLogout: function () {
            // debugger;
            var self = this;
            var url = this.getUrl({
                action: 'signout'
            });
            $.post(url, function (responce) {
                var _data = self.extractModelDataFromRespce(responce);
                Cache.setObject('AccountProfileID', null);
                self.set(_data);
                self.trigger('change');
                Sandbox.eventNotify('account:signed:out', null);
            });
        },
        editProfile: function (data) {
            var self = this;
            var url = this.getUrl({
                action: 'edit'
            });
            // debugger;
            $.post(url, {account: data}, function (responce) {
                var _data = self.extractModelDataFromRespce(responce);
                self.set(_data);
                self.trigger('change');
                Sandbox.eventNotify('account:profile:updated', _data);
            });
        },
        addAddress: function (data) {
            var self = this;
            var url = this.getUrl({
                action: 'addAddress'
            });
            // debugger;
            $.post(url, {address: data}, function (responce) {
                var _data = self.extractModelDataFromRespce(responce);
                self.set(_data);
                self.trigger('change');
                Sandbox.eventNotify('account:profile:address:added', _data);
            });
        },
        updateAddress: function (AddressID, data) {
            // debugger;
            var self = this;
            var url = this.getUrl({
                action: 'updateAddress'
            });
            data.AddressID = AddressID;
            $.post(url, {address: data}, function (responce) {
                var _data = self.extractModelDataFromRespce(responce);
                self.set(_data);
                self.trigger('change');
                Sandbox.eventNotify('account:profile:address:updated', _data);
            });
        },
        removeAddress: function (AddressID) {
            var self = this;
            var url = this.getUrl({
                action: 'removeAddress'
            });
            $.post(url, {AddressID: AddressID}, function (responce) {
                var _data = self.extractModelDataFromRespce(responce);
                self.set(_data);
                self.trigger('change');
                Sandbox.eventNotify('account:profile:address:removed', _data);
            });
        },
        changePassword: function (password, confirmation) {
            var self = this;
            var url = this.getUrl({
                action: 'updatePassword'
            });
            var data = {
                Password: password,
                ConfirmPassword: confirmation
            };
            $.post(url, data, function (responce) {
                var _data = self.extractModelDataFromRespce(responce);
                self.set(_data);
                self.trigger('change');
                Sandbox.eventNotify('account:profile:password:updated', _data);
            });
        }

    });

    // we have only one instance
    return new Account();

});