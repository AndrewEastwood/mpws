APP.Modules.register("model/mmodel", [], [
    'lib/jquery',
    'lib/underscore',
    'lib/backbone',
    'lib/storage',
], function (app, Sandbox, $, _, Backbone, Storage) {

    var _config = app.Page.getConfiguration();

    var MModel = Backbone.Model.extend({

        defaults: {

            data: {}, 

            realm: 'none',

            caller: '*',

            fn: '',

            token: _config.TOKEN
        },

        initialize: function (options) {

            this.attributes = _.extend({}, this.attributes, options);
            app.log('model MModel initialize', this);

        },

        getUrlData: function () {
            var _params = _(this.attributes).clone();

            delete _params.caller;
            delete _params.fn;

            return {
                caller: this.attributes.caller || '*',
                fn: this.attributes.fn,
                p: _params
                // $.extend(
                // // default params
                // {
                //     realm: "none"
                // },
                // // our custom params
                // _params,
                // // finally put this to make sure we have all essential parameters
                // {
                //     token: this.attributes.token || app.Page.getConfiguration().TOKEN
                // })
            };
        },

        fetch: function () {
            app.log('model MModel fetch from', _config.URL.apiJS);

            var self = this;
            $.post(_config.URL.apiJS, this.getUrlData(), function (data) {
                // app.log('data is received', data);
                if (data)
                    data = JSON.parse(data);
                else
                    data = {};
                self.set('data', self.parse(_dataInterfaceFn(data)));
            });

        }

        // fetch: function () {
        //     app.log(_logPrefix, 'MModel fetch', mpwsAPI);

        //     var self = this;
        //     var _params = _(this.attributes).clone();

        //     delete _params.caller;
        //     delete _params.fn;

        //     mpwsAPI.requestData({
        //         caller: this.caller,
        //         fn: this.fn,
        //         params: $.extend(_params, {
        //             realm: self.realm
        //         })
        //     }, function (error, data) {
        //         if (data)
        //             data = JSON.parse(data);
        //         else
        //             data = {};
        //         // if (typeof callback === "function") {
        //         //     callback.call(null, error, _dataInterfaceFn(data));
        //         // }
        //         self.parse(error, _dataInterfaceFn(data));
        //     });
        // }

    });

    function _dataInterfaceFn (data, type) {
        return {
            type: type || "none",
            data: data || {}
        }
    }

    return MModel;

});