define("plugin/shop/js/view/menuWishList", [
    'default/js/lib/sandbox',
    'default/js/view/mView',
    'default/js/plugin/hbs!plugin/shop/hbs/menuWishList'
], function (Sandbox, MView, tpl) {

    var MenuWishList = MView.extend({
        tagName: 'li',
        template: tpl,
        initialize: function () {
            var _self = this;
            Sandbox.eventSubscribe('shop:wishlist:info', function (data) {
                var _count = data && data.products && data.products.length || 0;
                if (_count)
                    _self.$('.counter').text(_count);
                else
                    _self.$('.counter').empty();
            });
        }
    });

    return MenuWishList;

});