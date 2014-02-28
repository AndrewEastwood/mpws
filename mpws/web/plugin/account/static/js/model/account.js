define("plugin/account/js/model/account", [
    'default/js/lib/sandbox',
    'cmn_jquery',
    'default/js/lib/underscore',
    'default/js/model/mModel'
], function (Sandbox, $, _, MModel) {

    var Model = MModel.getNew();

    var Account = Model.extend({

        source: 'shop',
        fn: 'shop_cart',
        initialize: function () {
            Model.prototype.initialize.call(this);

            var _self = this;
            // debugger;
            // this.updateUrl({
            //     action: 'INFO'
            // });

            Sandbox.eventSubscribe('account:signin', _.bind(_self.doLogin, _self));

            // this.on('change', function () {
            //     // _self.resetUrlOptions();
            //     Sandbox.eventNotify('shop:cart:info', _self.toJSON());
            // });

        },
        doLogin: function (data) {
            // debugger;
            var self = this;
            this.updateUrl({
                action: 'signin'
            });
            $.post(this.url, data, function (responce) {
                debugger;
            });
        },
        doLogout: function () {

        },
        showProfile: function () {

        },
        editProfile: function () {

        },

    });

    return Account;

});