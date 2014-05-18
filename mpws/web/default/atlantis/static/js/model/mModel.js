define("default/js/model/mModel", [
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'default/js/lib/url'
], function (_, Backbone, JSUrl) {

    function _factory() {
        // debugger;
        var MModel = Backbone.Model.extend({

            extras: {},
            // required parameters
            source: '',
            fn: '',
            // and user custom options
            urlOptions: {},

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

            getUrl: function (options) {
                var _options = _.extend({
                    source: this.source || '',
                    fn: this.fn || ''
                }, this.urlOptions ||{}, options);
                return APP.getApiLink(_options);
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

            fetch: function (options, fetchOptions) {
                this.url = !!options ? this.getUrl(options) : this.url;

                // var _url = (_.isString(this.url) && !_.isEmpty(this.url)) ? this.url : this.getUrl(options || {});
                // var _origUrl = this.url;
                // this.url = _url;
                var rez = Backbone.Model.prototype.fetch.call(this, fetchOptions || options || {});
                // this.url = _origUrl;
                return rez;
            }

        });

        MModel.getNew = _factory;

        return MModel;
    }

    return _factory();

});