define("plugin/shop/js/view/productItemShort", [
    'default/js/lib/sandbox',
    'default/js/view/mView',
    'default/js/plugin/hbs!plugin/shop/hbs/productItemShort'
], function (Sandbox, MView, tpl) {

    var ProductItemShort = MView.extend({
        // tagName: 'div',
        className: 'shop-product-item shop-product-item-short col-xs-12 col-sm-6 col-md-3 col-lg-3',
        template: tpl
    });

    return ProductItemShort;

});