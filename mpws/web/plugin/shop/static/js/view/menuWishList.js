define("plugin/shop/js/view/menuWishList", [
    'default/js/view/mView',
    'default/js/plugin/hbs!plugin/shop/hbs/menuWishList'
], function (MView, tpl) {

    var MenuWishList = MView.extend({
        tagName: 'li',
        template: tpl
    });

    return MenuWishList;

});