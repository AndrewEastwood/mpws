define('plugin/shop/site/js/model/productItemFull', [
    'plugin/shop/site/js/model/productItemBase',
    'plugin/shop/common/js/lib/utils'
], function (ProductItemBase, Utils) {

    var ProductItemFull = ProductItemBase.extend({
        url: function () {
            return APP.getApiLink({
                source: 'shop',
                fn: 'product',
                id: this.get('id')
            })
        }
    });

    return ProductItemFull;

});