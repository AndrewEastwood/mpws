define('plugin/shop/site/js/model/profileOrders', [
    'default/js/model/mModel',
    'plugin/shop/common/js/lib/utils'
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
                    _data.orders[key].boughts = ShopUtils.adjustProductItems(_data.orders[key].boughts);
            return _data;
        }
    });

    return TrackingSystem;

});