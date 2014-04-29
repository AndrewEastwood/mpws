define("plugin/shop/site/js/view/menuCart", [
    'default/js/lib/sandbox',
    'default/js/view/mView',
    'default/js/plugin/hbs!plugin/shop/site/hbs/menuCart'
], function (Sandbox, MView, tpl) {

    var MenuCart = MView.extend({
        tagName: 'li',
        template: tpl,
        initialize: function () {
            var _self = this;
            Sandbox.eventSubscribe('plugin:shop:cart:info', function (data) {
                var _count = data && data.info && data.info.productCount || 0;
                if (_count)
                    _self.$('.counter').text(_count);
                else
                    _self.$('.counter').empty();
            });
        }
    });

    return MenuCart;

});