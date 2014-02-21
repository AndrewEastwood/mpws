define('plugin/shop/js/collection/listProductLatest', [
    'default/js/lib/underscore',
    'default/js/collection/mCollection',
    'plugin/shop/js/model/productItemBase',
    'default/js/lib/url',
    'plugin/shop/js/lib/utils'
], function (_, MCollection, ProductItemBase, JSUrl, ShopUtils) {
    // debugger;

    var Collection = MCollection.getNew();

    var ListProductLatest = Collection.extend({
        source: 'shop',
        fn: 'shop_product_list_latest',
        model: ProductItemBase,
        initialize: function () {
            Collection.prototype.initialize.call(this);
            // debugger;
            this.updateUrl();
        },
        parse: function (data) {
            var products = ShopUtils.adjustProductEntry(data && data.shop);
            return _(products).map(function(item){ return item; });
        }
    });

    return ListProductLatest;
});