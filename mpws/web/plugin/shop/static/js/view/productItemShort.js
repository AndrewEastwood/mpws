define("plugin/shop/js/view/productItemShort", [
    'default/js/view/mView',
    'default/js/plugin/hbs!plugin/shop/hbs/productItemShort'
], function (MView, tpl) {

    var ProductItem = MView.extend({
        tagName: 'div',
        className: 'shop-product-item shop-product-item-short col-xs-12 col-sm-6 col-md-3 col-lg-3',
        template: tpl
    });

    return ProductItem;

});