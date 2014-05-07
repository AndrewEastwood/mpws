define("default/js/model/mModel", [
    'default/js/lib/sandbox',
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'default/js/lib/url'
], function (Sandbox, _, Backbone, JSUrl) {

    function _factory() {
        // debugger;
        var MModel = Backbone.Model.extend({

            extras: {},
            // required parameters
            source: '',
            fn: '',
            // and user custom options
            urlOptions: {},

            // resetUrlOptionsAfterFetch: true,

            clearErrors: function () {
                if (this.attributes && this.attributes.error)
                    this.attributes.error = false;
            },
            clearStates: function () {
                if (this.attributes && this.attributes.success)
                    this.attributes.success = false;
            },
            extractModelDataFromResponse: function (data) {
                return data && data[this.source] || {};
            },

            getSource: function () {
                return this.get(this._urlOptions.source);
            },

            resetUrl: function () {
                this.urlOptions = {};
                this.updateUrl();
            },

            updateUrl: function (options) {

                var self = this;
                var _options = options && _(options).clone() || {};
                var _url = new JSUrl(APP.config.URL_API);

                _url.query.token = APP.config.TOKEN;

                _(['source', 'fn']).each(function(key){
                    if (_options && typeof _options[key] !== "undefined"){
                        self[key] = _options[key];
                        delete _options[key];
                    }
                    self.urlOptions[key] = self[key];
                    _url.query[key] = self[key];
                });

                this.urlOptions = _.extend({}, this.urlOptions, _options);

                _(this.urlOptions).each(function (v, k) {
                    self.urlOptions[k] = !!v ? v : "";
                    _url.query[k] = !!v ? v : "";
                });

                this.url = _url.toString();
            },

            getUrl: function (options) {
                var _options = _.extend({}, this.urlOptions || {}, options);

                if (_.isEmpty(_options.source))
                    _options.source = this.source;
                if (_.isEmpty(_options.fn))
                    _options.fn = this.fn;

                return APP.getApiLink(_options);
                // return APP.getApiLink(options.source || this.source, options.fn || this.fn, options);

                // var self = this;
                // var _options = _.extend({}, this.urlOptions || {}, options);
                // var _url = new JSUrl(APP.config.URL_API);

                // _url.query.token = APP.config.TOKEN;

                // _(['source', 'fn']).each(function(key){
                //     if (_options && typeof _options[key] !== "undefined") {
                //         _url.query[key] = _options[key];
                //         delete _options[key];
                //     } else
                //         _url.query[key] = self[key];
                // });

                // _(_options).each(function (v, k) {
                //     _url.query[k] = !!v ? v : "";
                // });

                // return _url.toString();
            },

            getUrlOptions: function () {
                return this.urlOptions;
            },

            removeExtras: function (key) {
                delete this.extras[key];
            },

            setExtras: function (key, val) {
                this.extras[key] = val;
            },

            getExtras: function () {
                return this.extras;
            },

            hasExtras: function (key) {
                return typeof this.extras[key] !== "undefined";
            },

            fetch: function (options) {
                // debugger;
                if (typeof this.url !== "string")
                    this.updateUrl();
                var rez = Backbone.Model.prototype.fetch.call(this, options);

                // if (this.resetUrlOptionsAfterFetch)
                //     this.resetUrl();
                return rez;
            }
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

        MModel.getNew = _factory;

        return MModel;
    }

    return _factory();

});