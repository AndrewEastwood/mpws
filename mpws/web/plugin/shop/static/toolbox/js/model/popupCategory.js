define('plugin/shop/toolbox/js/model/popupCategory', [
    'default/js/model/mModel',
    'plugin/shop/common/js/lib/utils'
], function (MModel, ShopUtils) {

    var Model = MModel.getNew();
    var ToolboxOrderItem = Model.extend({
        source: 'shop',
        fn: 'shop_manage_cetegories',
        urlOptions: {
            action: 'get'
        },
        parse: function (data) {
            // debugger;
            var _data = this.extractModelDataFromResponse(data);

            if (_data.boughts)
                _data.boughts = ShopUtils.adjustProductItem({products: _data.boughts});

            return _data;
        }
    });

    return ToolboxOrderItem;

});