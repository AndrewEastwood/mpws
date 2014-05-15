define('plugin/shop/toolbox/js/model/origin', [
    'default/js/model/mModel',
    'plugin/shop/common/js/lib/utils'
], function (MModel, ShopUtils) {

    var Model = MModel.getNew();
    var ToolboxOrderItem = Model.extend({
        source: 'shop',
        fn: 'shop_manage_origins',
        urlOptions: {
            action: 'get'
        },
        parse: function (data) {
            var _data = this.extractModelDataFromResponse(data);
            return _data;
        }
    });

    return ToolboxOrderItem;

});