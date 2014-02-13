define('plugin/shop/js/collection/listLatestProducts', [
    'default/js/lib/underscore',
    'default/js/collection/mCollection',
    'plugin/shop/js/model/productItemBase',
    'default/js/lib/url',
    'plugin/shop/js/lib/utils'
], function (_, MCollection, ProductItemBase, JSUrl, ShopUtils) {

    var ListLatestProduct = MCollection.extend({
        model: ProductItemBase,
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

    return ListLatestProduct;
});