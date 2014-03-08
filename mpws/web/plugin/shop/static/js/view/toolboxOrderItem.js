define("plugin/shop/js/view/toolboxOrderItem", [
    'default/js/lib/sandbox',
    'default/js/view/mView',
    'default/js/plugin/hbs!plugin/shop/hbs/toolbox/toolboxOrderItem'
], function (Sandbox, MView, tpl) {

    var OrderItem = MView.extend({
        // tagName: 'div',
        className: 'shop-order-item',
        template: tpl
    });

    return OrderItem;

});