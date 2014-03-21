define("plugin/shop/js/view/menuWishList", [
    'default/js/lib/sandbox',
    'default/js/view/mView',
    'plugin/shop/js/model/wishList',
    'default/js/plugin/hbs!plugin/shop/hbs/site/menuWishList'
], function (Sandbox, MView, ModelWishListInstance, tpl) {

    var MenuWishList = MView.extend({
        tagName: 'li',
        template: tpl,
        initialize: function () {
            var _self = this;
            Sandbox.eventSubscribe('plugin:shop:wishlist:info', function (data) {
                var _count = data && data.products && data.products.length || 0;
                if (_count)
                    _self.$('.counter').text(_count);
                else
                    _self.$('.counter').empty();
            });
            ModelWishListInstance.getInfo();
        }
    });

    return MenuWishList;

});