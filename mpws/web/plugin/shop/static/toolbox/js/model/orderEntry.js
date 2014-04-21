define('plugin/shop/toolbox/js/model/orderEntry', [
    'default/js/model/mModel',
    'plugin/shop/common/js/lib/utils'
], function (MModel, ShopUtils) {

    var Model = MModel.getNew();
    var ToolboxOrderItem = Model.extend({
        source: 'shop',
        fn: 'shop_managed_order_entry',
        parse: function (data) {
            // debugger;
            var _data = this.extractModelDataFromRespce(data);

            _data.boughts = ShopUtils.adjustProductEntry({products: _data.boughts});

            return _data;
        }
    });

    return ToolboxOrderItem;

});