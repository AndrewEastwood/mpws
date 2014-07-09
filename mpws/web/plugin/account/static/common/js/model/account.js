define("plugin/account/common/js/model/account", [
    'default/js/lib/sandbox',
    'cmn_jquery',
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'default/js/lib/cache'
], function (Sandbox, $, _, Backbone, Cache) {

    // var Model = MModel.getNew();

    var Account = Backbone.Model.extend({
        idAttribute: "ID",
        defaults: {
            items: {},
            info: {},
            promo: {},
            account: {}
        },
        url: function () {
            var _params =  {
                source: 'account',
                fn: 'account'
            };
            if (!this.isNew())
                _params.id = this.id;
            return APP.getApiLink(_params);
        },
        source: 'account',
        fn: 'profile',
        initialize: function () {
            // debugger;
            this.getStatus();

            // Sandbox.eventSubscribe('plugin:account:signout', _.bind(this.doLogout, this));
            Sandbox.eventSubscribe('plugin:account:status', _.bind(this.getStatus, this));
        },
        getStatus: function (options) {
            var self = this;
            var url = this.getUrl({
                action: 'status'
            });
            $.post(url, function (response) {
                var _data = self.extractModelDataFromResponse(response);
                Cache.setObject('AccountProfileID', _data.profile && _data.profile.ID);
                self.set(_data);
                self.trigger('change');
                Sandbox.eventNotify('plugin:account:status:received', _data.profile);
            });
        },
        doLogin: function (data) {
            // debugger;
            var self = this;
            var url = this.getUrl({
                action: 'signin'
            });
            $.post(url, {credentials: data}, function (response) {
                var _data = self.extractModelDataFromResponse(response);
                if (_data.profile && _data.profile.ID) {
                    Cache.setObject('AccountProfileID', _data.profile && _data.profile.ID);
                    self.set(_data);
                    Sandbox.eventNotify('plugin:account:signed:in', _data.profile);
                } else {
                    Cache.setObject('AccountProfileID', null);
                    self.set(null);
                }
                self.trigger('change');
            });
        },
        doLogout: function () {
            // debugger;
            var self = this;
            var url = this.getUrl({
                action: 'signout'
            });
            $.post(url, function (response) {
                var _data = self.extractModelDataFromResponse(response);
                Cache.setObject('AccountProfileID', null);
                self.set(_data);
                self.trigger('change');
                Sandbox.eventNotify('plugin:account:signed:out', null);
            });
        },
        editProfile: function (data) {
            var self = this;
            var url = this.getUrl({
                action: 'edit'
            });
            // debugger;
            $.post(url, {account: data}, function (response) {
                var _data = self.extractModelDataFromResponse(response);
                self.set(_data);
                self.trigger('change');
                Sandbox.eventNotify('plugin:account:profile:updated', _data);
            });
        },
        addAddress: function (data) {
            var self = this;
            var url = this.getUrl({
                action: 'addAddress'
            });
            // debugger;
            $.post(url, {address: data}, function (response) {
                var _data = self.extractModelDataFromResponse(response);
                self.set(_data);
                self.trigger('change');
                Sandbox.eventNotify('plugin:account:profile:address:added', _data);
            });
        },
        updateAddress: function (AddressID, data) {
            // debugger;
            var self = this;
            var url = this.getUrl({
                action: 'updateAddress'
            });
            data.AddressID = AddressID;
            $.post(url, {address: data}, function (response) {
                var _data = self.extractModelDataFromResponse(response);
                self.set(_data);
                self.trigger('change');
                Sandbox.eventNotify('plugin:account:profile:address:updated', _data);
            });
        },
        removeAddress: function (AddressID) {
            var self = this;
            var url = this.getUrl({
                action: 'removeAddress'
            });
            $.post(url, {AddressID: AddressID}, function (response) {
                var _data = self.extractModelDataFromResponse(response);
                self.set(_data);
                self.trigger('change');
                Sandbox.eventNotify('plugin:account:profile:address:removed', _data);
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
            $.post(url, data, function (response) {
                var _data = self.extractModelDataFromResponse(response);
                self.set(_data);
                self.trigger('change');
                Sandbox.eventNotify('plugin:account:profile:password:updated', _data);
            });
        }

    });

    // we have only one instance
    return new Account();

});