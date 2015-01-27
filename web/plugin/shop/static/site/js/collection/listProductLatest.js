define('plugin/shop/site/js/collection/listProductLatest', [
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'plugin/shop/site/js/model/product'
], function (_, Backbone, ModelProduct) {

    var ListProductLatest = Backbone.Collection.extend({
        model: ModelProduct,
        url: APP.getApiLink({
            source: 'shop',
            fn: 'products',
            type: 'latest',
            limit: 16
        }),
        parse: function (data) {
            return data.items;
        }
    });

    return ListProductLatest;
});