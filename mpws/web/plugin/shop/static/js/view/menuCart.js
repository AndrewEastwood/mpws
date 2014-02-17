define("plugin/shop/js/view/menuCart", [
    'default/js/view/mView',
    'default/js/plugin/hbs!plugin/shop/hbs/menuCart'
], function (MView, tpl) {

    var MenuCart = MView.extend({
        tagName: 'li',
        template: tpl
    });

    return MenuCart;

});