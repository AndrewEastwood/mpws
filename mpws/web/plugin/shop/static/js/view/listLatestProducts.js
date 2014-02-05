define("plugin/shop/js/view/listLatestProducts", [
    'default/js/lib/underscore',
    'default/js/view/mView',
    'plugin/shop/js/collection/listLatestProducts',
    'plugin/shop/js/view/productItemShort'
], function (_, MView, CollListLatestProducts, ProductItemShort) {

    var ListLatestProducts = MView.extend({
        tagName: 'div',
        className: 'shop-product-list shop-product-list-latest',
        collection: new CollListLatestProducts(),
        itemViewClass: ProductItemShort
    });

    return ListLatestProducts;

});