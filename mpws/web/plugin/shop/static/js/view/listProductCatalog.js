define("plugin/shop/js/view/listProductCatalog", [
    'default/js/lib/underscore',
    'default/js/view/mView',
    'plugin/shop/js/collection/listProductCatalog',
    'plugin/shop/js/view/productItemShort',
    'default/js/plugin/hbs!plugin/shop/hbs/productCatalog',
], function (_, MView, CollListProductCatalog, ProductItemShort, tpl) {

    var ListProductCatalog = MView.extend({
        tagName: 'div',
        className: 'shop-product-list shop-product-list-catalog',
        collection: new CollListProductCatalog(),
        itemViewClass: ProductItemShort,
        template: tpl,
        events: {

        }
    });

    return ListProductCatalog;

});