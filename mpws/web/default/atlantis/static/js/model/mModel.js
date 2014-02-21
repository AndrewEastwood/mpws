define("default/js/model/mModel", [
    'default/js/lib/sandbox',
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'default/js/lib/url'
], function (Sandbox, _, Backbone, JSUrl) {

    return {
        extend: function (child) {
            var MModel = Backbone.Model.extend({

                getBase: function () {
                    return MModel.prototype;
                },

                extras: {},
                // required parameters
                token: app.config.TOKEN,
                source: '',
                fn: '',
                // and user custom options
                // urlOptions: {},

                getSource: function () { 
                    return this.get(this._urlOptions.source);
                },

                resetUrlOptions: function () {
                    this.updateUrlOptions({});
                },

                updateUrlOptions: function (options) {

                    var self = this;
                    var _options = _(options).clone();
                    var _url = new JSUrl(app.config.URL_API);

                    _(['token', 'source', 'fn']).each(function(key){
                        if (_options && typeof _options[key] !== "undefined"){
                            self[key] = _options[key];
                            delete _options[key];
                        }
                        _url.query[key] = self[key];
                    });


                    // debugger;
                    // _(this._urlOptions).each(function (defaultValue, key) {
                    //     self._urlOptions[key] = options[key] || defaultValue;
                    // });

                    // debugger;
                    // var _urlData = _.extend({}, this._urlOptions, options);


                    _(_options).each(function (v, k) {
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

            // debugger;
            // var MModel = Backbone.Model.extend(_.extend({}, _mmodeObj));

            if (_.isObject(child))
                return MModel.extend(child);

            return MModel;
        }

    }

});