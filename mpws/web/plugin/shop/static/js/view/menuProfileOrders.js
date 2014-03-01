define("plugin/shop/js/view/menuProfileOrders", [
    'default/js/lib/sandbox',
    'default/js/view/mView',
    'default/js/plugin/hbs!plugin/shop/hbs/menuProfileOrders'
], function (Sandbox, MView, tpl) {

    var MenuCart = MView.extend({
        tagName: 'li',
        template: tpl,
        initialize: function () {
            var _self = this;
            // this should react on new order made by active profile
            Sandbox.eventSubscribe('shop:profile:orders', function (data) {
                var _count = data && data.orders && data.orders.length || 0;
                if (_count)
                    _self.$('.counter').text(_count);
                else
                    _self.$('.counter').empty();
            });
        }
    });

    return MenuCart;

});