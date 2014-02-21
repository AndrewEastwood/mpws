define("default/js/collection/mCollection", [
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'default/js/lib/url'
], function (_, Backbone, JSUrl) {

    function _factory () {
        // debugger;
        var MCollection = Backbone.Collection.extend({

            extras: {},
            // required parameters
            source: '',
            fn: '',
            // and user custom options
            urlOptions: {},

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
                if (typeof this.url !== "string")
                    this.updateUrl();
                var rez = Backbone.Collection.prototype.fetch.call(this, options);
                return rez;
            }
        });

        MCollection.getNew = _factory;

        return MCollection;
    }

    return _factory();

});