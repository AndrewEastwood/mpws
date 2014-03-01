define('plugin/shop/js/model/profileOrders', [
    'default/js/model/mModel',
    'plugin/shop/js/lib/utils'
], function (MModel, ShopUtils) {

    var Model = MModel.getNew();
    var TrackingSystem = Model.extend({
        source: 'shop',
        fn: 'shop_profile_orders',
        parse: function (data) {
            return data && data.shop;
            // debugger;
            var products = ShopUtils.adjustProductEntry(data && data.shop);
            return {
                products: _(products).map(function(item){ return item; })
            };
        }
    });

    return TrackingSystem;

});