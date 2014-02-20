define("default/js/model/mModel", [
    'default/js/lib/sandbox',
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'default/js/lib/url'
], function (Sandbox, _, Backbone, JSUrl) {

    var MModel = Backbone.Model.extend({

        _extras: {},
        _urlOptions: {
            // required parameters
            token: app.config.TOKEN,
            source: '',
            fn: ''
            // and user custom options
        },

        getSource: function () { 
            return this.get(this._urlOptions.source);
        },

        updateUrlOptions: function (options) {
            this._urlOptions = _.extend({}, this._urlOptions, options);

            var _url = new JSUrl(app.config.URL_API);

            _(this._urlOptions).each(function (v, k) {
                _url.query[k] = !!v ? v : "";
            });

            _(this._urlData).each(function (v, k) {
                _url.query[k] = !!v ? v : "";
            });

            this.url = _url.toString();
        },

        getUrlOption: function (key) {
            return this._urlOptions[key];
        },

        getUrlOptions: function () {
            return this._urlOptions;
        },

        setExtras: function (key, val) {
            this._extras[key] = val;
        },

        getExtras: function () {
            return this._extras;
        },

        // fetch: function (options) {
        //     options = options || {};
        //     var _success = options.success;
        //     var _self = this;
        //     options.success = function (model, resp, options) {
        //         if (typeof _success === "function")
        //             _success.call(_self, model, resp, options);
        //         Sandbox.eventNotify('mmodel:dataReceived', {model: model, resp: resp, options: options});
        //     }
        //     return Backbone.Model.prototype.fetch.call(this, options);
        // }

    });

    return MModel;

});