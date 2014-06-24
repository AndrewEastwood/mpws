define('plugin/shop/site/js/collection/listProductWish', [
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'plugin/shop/common/js/lib/utils'
], function (_, Backbone, ShopUtils) {

    var ListProductWish = Backbone.Collection.extend({
        url: APP.getApiLink({
            source: 'shop',
            fn: 'wish'
        }),
        parse: function (data) {
            var products = ShopUtils.adjustProductItems(data && data.items);
            return _(products).map(function(item){ return item; });
        }
    });

    return new ListProductWish();
});