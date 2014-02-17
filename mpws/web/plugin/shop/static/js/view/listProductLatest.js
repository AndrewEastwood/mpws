define("plugin/shop/js/view/listProductLatest", [
    'default/js/lib/underscore',
    'default/js/view/mView',
    'plugin/shop/js/collection/listProductLatest',
    'plugin/shop/js/view/productItemShort'
], function (_, MView, CollListProductLatest, ProductItemShort) {

    var ListProductLatest = MView.extend({
        // tagName: 'div',
        className: 'shop-product-list shop-product-list-latest',
        collection: new CollListProductLatest(),
        itemViewClass: ProductItemShort,
        autoRender: true
    });

    return ListProductLatest;

});