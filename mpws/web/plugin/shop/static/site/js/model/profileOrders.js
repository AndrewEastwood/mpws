define('plugin/shop/js/model/profileOrders', [
    'default/js/model/mModel',
    'plugin/shop/js/lib/utils'
], function (MModel, ShopUtils) {

    var Model = MModel.getNew();
    var TrackingSystem = Model.extend({
        source: 'shop',
        fn: 'shop_profile_orders',
        parse: function (data) {
            var _data = data && data.shop;

            // debugger;
            if (_data.orders)
                for (var key in _data.orders)
                    _data.orders[key].Boughts = ShopUtils.adjustProductEntry({products: _data.orders[key].Boughts});
            return _data;
        }
    });

    return TrackingSystem;

});