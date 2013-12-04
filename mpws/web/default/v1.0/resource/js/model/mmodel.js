APP.Modules.register("model/mmodel", [], [
    'lib/jquery',
    'lib/underscore',
    'lib/backbone',
    'lib/storage',
], function (app, Sandbox, $, _, Backbone, Storage) {

    var MModel = Backbone.Model.extend({

        fetch: function () {
            app.log(_logPrefix, 'getShopLocation', mpwsAPI);

            var self = this;
            var _params = _(this.attributes).clone();

            delete _params.caller;
            delete _params.fn;

            mpwsAPI.requestData({
                caller: this.caller,
                fn: this.fn,
                params: $.extend(_params, {
                    realm: self.realm
                })
            }, function (error, data) {
                if (data)
                    data = JSON.parse(data);
                else
                    data = {};
                // if (typeof callback === "function") {
                //     callback.call(null, error, _dataInterfaceFn(data));
                // }
                self.parse(error, _dataInterfaceFn(data));
            });
        }

    });

    function _dataInterfaceFn (data, type) {
        return {
            type: type || "none",
            data: data || {}
        }
    }

    return MModel;

});