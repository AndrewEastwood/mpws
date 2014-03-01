define("plugin/account/js/model/account", [
    'default/js/lib/sandbox',
    'cmn_jquery',
    'default/js/lib/underscore',
    'default/js/model/mModel'
], function (Sandbox, $, _, MModel) {

    var Model = MModel.getNew();

    var Account = Model.extend({
        source: 'account',
        fn: 'signin',
        doLogin: function (data) {
            // debugger;
            var self = this;
            this.updateUrl({
                action: 'signin'
            });
            $.post(this.url, {credentials: data}, function (responce) {
                var _data = self.extractModelDataFromRespce(responce);
                self.set(_data);
                self.trigger('change');
            });
        },
        doLogout: function () {

        },
        showProfile: function () {

        },
        editProfile: function () {

        },

    });

    // we have only one instance
    return new Account();

});