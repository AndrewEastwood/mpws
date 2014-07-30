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
        url: function () {
            var _params =  {
                source: 'account',
                fn: 'account'
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
        update: function (data) {
            var self = this;
            data.id = this.id;
            this.save(data, {patch: true});
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
            // var url = this.getUrl({
            //     action: 'updatePassword'
            // });
            var data = {
                Password: password,
                ConfirmPassword: confirmation
            };
            this.update(data);
            // $.post(url, data, function (response) {
            //     var _data = self.extractModelDataFromResponse(response);
            //     self.set(_data);
            //     self.trigger('change');
            //     Sandbox.eventNotify('plugin:account:profile:password:updated', _data);
            // });
        }

    });

    // --- we have only one instance
    return Account;

});