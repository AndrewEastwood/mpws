define("plugin/shop/site/js/view/menuCompare", [
    'default/js/lib/sandbox',
    'default/js/view/mView',
    'plugin/shop/site/js/model/productsCompare',
    'default/js/plugin/hbs!plugin/shop/site/hbs/menuCompare'
], function (Sandbox, MView, ModelProductsCompareInstance, tpl) {

    var MenuCompare = MView.extend({
        tagName: 'li',
        template: tpl,
        initialize: function () {
            var _self = this;
            Sandbox.eventSubscribe('plugin:shop:compare:info', function (data) {
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