define([
    'underscore',
    'backbone',
    'plugins/shop/site/js/model/product'
], function (_, Backbone, ModelProduct) {

    var ListProductLatest = Backbone.Collection.extend({
        model: ModelProduct,
        // url: APP.getApiLink({
        //     source: 'shop',
        //     fn: 'products',
        //     type: 'latest',
        //     limit: 16
        // }),
        initialize: function (options) {
            options = options || {};
            this.url = APP.getApiLink(_.extend({
                source: 'shop',
                fn: 'products',
                limit: 16
            }, _(options).omit('design')));
        },
        parse: function (data) {
            return data.items;
        }
    });

    return ListProductLatest;
});