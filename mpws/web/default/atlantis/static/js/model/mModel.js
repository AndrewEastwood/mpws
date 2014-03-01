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

            extractModelDataFromRespce: function (data) {
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
                var _url = new JSUrl(app.config.URL_API);

                _url.query.token = app.config.TOKEN;

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

            getUrlOptions: function () {
                return this.urlOptions;
            },

            setExtras: function (key, val) {
                this.extras[key] = val;
            },

            getExtras: function () {
                return this.extras;
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