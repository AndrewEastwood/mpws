define('plugin/shop/js/collection/toolboxListOrders', [
    'default/js/lib/underscore',
    'default/js/collection/mCollection',
    'plugin/shop/js/model/toolboxOrderItem',
    'default/js/lib/url',
    'plugin/shop/js/lib/utils'
], function (_, MCollection, ToolboxOrderItem, JSUrl, ShopUtils) {
    // debugger;

    var Collection = MCollection.getNew();

    var ListOrders = Collection.extend({
        source: 'shop',
        fn: 'shop_manage_orders',
        model: ToolboxOrderItem,
        initialize: function () {
            Collection.prototype.initialize.call(this);
            // debugger;
            this.updateUrl();
        },
        parse: function (data) {
            var _data = this.extractModelDataFromRespce(data);
            return _data.orders;
            // var products = ShopUtils.adjustProductEntry(data && data.shop);
            // return _(products).map(function(item){ return item; });
        }
    });

    return ListOrders;
});l