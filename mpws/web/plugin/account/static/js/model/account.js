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
                var _data = self.extractModelDataFromRespce(responce);
                Cache.setObject('AccountProfileID', null);
                self.set(_data);
                self.trigger('change');
                Sandbox.eventNotify('account:signed:out', null);
            });
        },
        editProfile: function (data) {
            var self = this;
            this.updateUrl({
                action: 'edit'
            });
            // debugger;
            $.post(this.url, {account: data}, function (responce) {
                var _data = self.extractModelDataFromRespce(responce);
                self.set(_data);
                self.trigger('change');
                Sandbox.eventNotify('account:profile:updated', _data);
            });
        },
        addAddress: function (data) {
            var self = this;
            this.updateUrl({
                action: 'addAddress'
            });
            // debugger;
            $.post(this.url, {address: data}, function (responce) {
                var _data = self.extractModelDataFromRespce(responce);
                self.set(_data);
                self.trigger('change');
                Sandbox.eventNotify('account:profile:address:added', _data);
            });
        },
        updateAddress: function (AddressID, data) {
            // debugger;
            var self = this;
            this.updateUrl({
                action: 'updateAddress'
            });
            data.AddressID = AddressID;
            $.post(this.url, {address: data}, function (responce) {
                var _data = self.extractModelDataFromRespce(responce);
                self.set(_data);
                self.trigger('change');
                Sandbox.eventNotify('account:profile:address:updated', _data);
            });
        },
        removeAddress: function (AddressID) {
            var self = this;
            this.updateUrl({
                action: 'removeAddress'
            });
            $.post(this.url, {AddressID: AddressID}, function (responce) {
                var _data = self.extractModelDataFromRespce(responce);
                self.set(_data);
                self.trigger('change');
                Sandbox.eventNotify('account:profile:address:removed', _data);
            });
        },
        changePassword: function (password, confirmation) {
            var self = this;
            this.updateUrl({
                action: 'updatePassword'
            });
            var data = {
                Password: password,
                ConfirmPassword: confirmation
            };
            $.post(this.url, data, function (responce) {
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