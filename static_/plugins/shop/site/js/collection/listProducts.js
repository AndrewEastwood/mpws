define([
    'underscore',
    'backbone',
    'plugins/shop/site/js/model/product'
], function (_, Backbone, ModelProduct) {

    var ListProductLatest = Backbone.Collection.extend({
        model: ModelProduct,
        url: function () {
            var options = this.getOptions();
            return APP.getApiLink(_.extend({
                source: 'shop',
                fn: 'products',
                limit: 16
            }, _(options).omit('design')));
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