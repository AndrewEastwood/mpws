define('plugin/shop/site/js/collection/listProductLatest', [
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'plugin/shop/site/js/model/productItemBase',
    'plugin/shop/common/js/lib/utils'
], function (_, Backbone, ProductItemBase, ShopUtils) {

    var ListProductLatest = Backbone.Collection.extend({
        model: ProductItemBase,
        url: APP.getApiLink({
            source: 'shop',
            fn: 'products',
            type: 'latest'
        }),
        parse: function (data) {
            // debugger;
            var products = ShopUtils.adjustProductItems(data && data.items);
            return _(products).map(function(item){ return item; });
        }
    });

    return ListProductLatest;
});