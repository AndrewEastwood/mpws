define('plugin/shop/js/collection/listLatestProducts', [
    'default/js/lib/underscore',
    'default/js/collection/mCollection',
    'plugin/shop/js/model/productItem',
    'default/js/lib/url',
    'plugin/shop/js/lib/utils'
], function (_, MCollection, ProductItem, JSUrl, ShopUtils) {

    var ProductListLatest = MCollection.extend({
        model: ProductItem,
        initialize: function () {
            // debugger;
            this.updateUrlOptions({
                source: 'shop',
                fn: 'shop_product_list_latest'
            });
        },
        parse: function (data) {
            var products = ShopUtils.adjustProductEntry(data && data.shop);
            return _(products).map(function(item){ return item; });
        }
    });

    return ProductListLatest;
});