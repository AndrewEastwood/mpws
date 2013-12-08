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

            token: _config.TOKEN,

            urldata: {}
        },

        initialize: function (options) {
            this.attributes = _.extend({}, this.attributes, options);
            // app.log(true, 'model MModel initialize', this);
        },

        getUrlData: function () {
            var _params = _(this.attributes).clone();

            delete _params.caller;
            delete _params.fn;

            return {
                caller: this.attributes.caller || '*',
                fn: this.attributes.fn,
                p: $.extend({realm:_params.realm}, _params.urldata, {token:_params.token})
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

        setUrlData: function (key, value) {
            if (!key)
                return;

            var urlData = this.get("urldata");
            var urlDataOrigin = _.extend({}, urlData);

            if (value)
                urlData[key] = value;
            else if (value === null)
                delete urlData[key];

            this.set("urldata", urlData);
            // app.log(true, 'new usrl data is ', urlData);

            // app.log(true, this.get('fn') + ' origin url data was', urlDataOrigin);
            // app.log(true, this.get('fn') + ' now it is', urlData);
            if (!_.isEqual(urlDataOrigin, urlData)) {
                app.log(true, this.get('fn') + ' urldata is changed, doing fetch new data');
                this.fetch();
            }
        },

        // default method
        // you can override this to modify data before pushing into template
        parse: function (data) {
            return data;
        },

        fetch: function () {
            // app.log(true, 'model MModel fetch from', _config.URL.apiJS);

            var self = this;
            $.post(_config.URL.apiJS, this.getUrlData(), function (data) {
                // app.log(true, 'data is received', data);
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