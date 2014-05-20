define("plugin/toolbox/toolbox/js/model/auth", [
    'default/js/lib/sandbox',
    'cmn_jquery',
    'default/js/lib/underscore',
    'default/js/model/mModel',
    'default/js/lib/cache'
], function (Sandbox, $, _, MModel, Cache) {

    var Model = MModel.getNew();

    var Bridge = Model.extend({
        source: 'toolbox',
        fn: 'auth',
        initialize: function () {
            // debugger;
            Sandbox.eventSubscribe('plugin:toolbox:status', _.bind(this.getStatus, this));
            Sandbox.eventSubscribe('plugin:toolbox:logout', _.bind(this.doLogout, this));
        },
        getStatus: function (options) {
            var self = this;
            var url = this.getUrl({
                action: 'status'
            });
            $.post(url, function (response) {
                Cache.setObject('AdminProfileID', response.profile && response.profile.ID);
                self.set(response);
                self.trigger('change');
                Sandbox.eventNotify('plugin:toolbox:status:received', response.profile);
            }).error(function(){
                Sandbox.eventNotify('plugin:toolbox:status:received', null);
            });
        },
        doLogin: function (data) {
            // debugger;
            var self = this;
            var url = this.getUrl({
                action: 'signin'
            });
            $.post(url, {credentials: data}, function (response) {
                var _profileID = response.profile && response.profile.ID;
                Cache.setObject('AdminProfileID', _profileID);
                self.set(response);
                self.trigger('change');
                if (_profileID)
                    Sandbox.eventNotify('plugin:toolbox:signed:in', response.profile);
            }).error(function(){
                debugger;
            });
        },
        doLogout: function () {
            // debugger;
            var self = this;
            var url = this.getUrl({
                action: 'signout'
            });
            $.post(url, function (response) {
                Cache.setObject('AdminProfileID', null);
                self.set(response);
                self.trigger('change');
                Sandbox.eventNotify('plugin:toolbox:signed:out', null);
            }).error(function(){
                debugger;
            });
        }

    });

    // we have only one instance
    return new Bridge();

});