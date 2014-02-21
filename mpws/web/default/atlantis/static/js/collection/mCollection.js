define("default/js/collection/mCollection", [
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'default/js/lib/url'
], function (_, Backbone, JSUrl) {

    return {
        getNew: function () {
            return Backbone.Collection.extend({
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
                    var self = this;

                    // debugger;
                    _(this._urlOptions).each(function (defaultValue, key) {
                        self._urlOptions[key] = options[key] || defaultValue;
                    });

                    // debugger;
                    var _urlData = _.extend({}, this._urlOptions, options);

                    var _url = new JSUrl(app.config.URL_API);

                    _(_urlData).each(function (v, k) {
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
                }
                

            });
        }
    }

});