define("plugin/shop/js/view/menuCompare", [
    'default/js/lib/sandbox',
    'default/js/view/mView',
    'plugin/shop/js/model/productsCompare',
    'default/js/plugin/hbs!plugin/shop/hbs/menuCompare'
], function (Sandbox, MView, ModelProductsCompareInstance, tpl) {

    var MenuCompare = MView.extend({
        tagName: 'li',
        template: tpl,
        initialize: function () {
            var _self = this;
            Sandbox.eventSubscribe('shop:compare:info', function (data) {
                var _count = data && data.products && data.products.length || 0;
                if (_count)
                    _self.$('.counter').text(_count);
                else
                    _self.$('.counter').empty();
            });
            ModelProductsCompareInstance.getInfo();
        }
    });

    return MenuCompare;

});