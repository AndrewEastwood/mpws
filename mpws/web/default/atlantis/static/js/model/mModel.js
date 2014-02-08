define("default/js/model/mModel", [
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'default/js/lib/url'
], function (_, Backbone, JSUrl) {

    var MModel = Backbone.Model.extend({

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
        }

    });

    return MModel;

});