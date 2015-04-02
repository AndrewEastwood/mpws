define([
    'underscore',
    'backbone',
    'plugins/shop/site/js/model/product'
], function (_, Backbone, ModelProduct) {

    var ListProductLatest = Backbone.Collection.extend({
        model: ModelProduct,
        url: function () {
            var options = _.extend({
                limit: 16
            }, _(this.getOptions() || {}).omit('design'));
            return APP.getApiLink('shop', 'products', options);
        },
        setOptions: function (options) {
            this.options = options;
        },
        getOptions: function () {
            return this.options;
        },
        parse: function (data) {
            return data.items;
        }
    });

    return ListProductLatest;
});