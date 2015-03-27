define([
    'backbone',
    'handlebars',
    'text!plugins/shop/site/hbs/menuProfileOrders.hbs'
], function (Backbone, Handlebars, tpl) {

    var MenuCart = Backbone.View.extend({
        tagName: 'a',
        attributes: {
            href: '/#!/shop/profile/orders'
        },
        template: Handlebars.compile(tpl), // check
        initialize: function () {
            var _self = this;
            // this should react on new order made by active profile
            APP.Sandbox.eventSubscribe('shop:profile:orders', function (data) {
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