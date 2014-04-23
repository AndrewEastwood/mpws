define('plugin/shop/site/js/model/productItemFull', [
    'plugin/shop/site/js/model/productItemBase',
    'plugin/shop/common/js/lib/utils'
], function (ProductItemBase, Utils) {

    var Model = ProductItemBase.getNew();
    var ProductItemFull = Model.extend({
        source: 'shop',
        fn: 'shop_product_item',
        parse: function (data) {
            // debugger;
            var products = Utils.adjustProductEntry(data && data.shop || {});
            return products[this.urlOptions.productID] || {};
        }
    });

    return ProductItemFull;

});