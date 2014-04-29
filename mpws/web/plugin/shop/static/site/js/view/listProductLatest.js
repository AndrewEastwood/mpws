define("plugin/shop/site/js/view/listProductLatest", [
    'default/js/lib/underscore',
    'default/js/view/mView',
    'plugin/shop/site/js/collection/listProductLatest',
    'plugin/shop/site/js/view/productItemShort'
], function (_, MView, CollListProductLatest, ProductItemShort) {

    // debugger;
    var ListProductLatest = MView.extend({
        // tagName: 'div',
        className: 'shop-product-list shop-product-list-latest',
        collection: new CollListProductLatest(),
        itemViewClass: ProductItemShort,
        autoRender: true
    });

    return ListProductLatest;

});