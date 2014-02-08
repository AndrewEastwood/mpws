define('plugin/shop/js/model/productItemFull', [
    'plugin/shop/js/model/productItemBase',
    'plugin/shop/js/lib/utils'
], function (ProductItemBase, Utils) {

    var ProductItemFull = ProductItemBase.extend({
        parse: function (data) {
            var products = Utils.adjustProductEntry(data && data.shop || {});
            return products[this.getUrlOption('productID')] || {};
        }
    });

    return ProductItemFull;

});