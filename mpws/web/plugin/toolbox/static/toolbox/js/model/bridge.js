define("plugin/toolbox/toolbox/js/model/bridge", [
    'default/js/lib/sandbox',
    'cmn_jquery',
    'default/js/lib/underscore',
    'default/js/model/mModel',
    'default/js/lib/cache'
], function (Sandbox, $, _, MModel, Cache) {

    var Model = MModel.getNew();

    var Bridge = Model.extend({
        source: 'toolbox',
        fn: 'bridge',
        initialize: function () {
            // debugger;
            // this.getStatus();
            // Sandbox.eventSubscribe('account:signout', _.bind(this.doLogout, this));
            Sandbox.eventSubscribe('plugin:toolbox:status', _.bind(this.getStatus, this));
            Sandbox.eventSubscribe('plugin:toolbox:logout', _.bind(this.doLogout, this));
        },
        getStatus: function (options) {
            var self = this;
            var url = this.getUrl({
                action: 'status'
            });
            $.post(url, function (response) {
                var _data = self.extractModelDataFromResponse(response);
                Cache.setObject('AdminProfileID', _data.profile && _data.profile.ID);
                self.set(_data);
                self.trigger('change');
                Sandbox.eventNotify('plugin:toolbox:status:received', _data.profile);
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
                var _profileID = _data.profile && _data.profile.ID;
                Cache.setObject('AdminProfileID', _profileID);
                self.set(_data);
                self.trigger('change');
                if (_profileID)
                    Sandbox.eventNotify('plugin:toolbox:signed:in', _data.profile);
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
                Cache.setObject('AdminProfileID', null);
                self.set(_data);
                self.trigger('change');
                Sandbox.eventNotify('plugin:toolbox:signed:out', null);
            });
        }

    });

    // we have only one instance
    return new Bridge();

});