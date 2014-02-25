define('plugin/shop/js/model/trackingStatus', [
    'default/js/model/mModel',
], function (MModel) {

    var Model = MModel.getNew();
    var TrackingSystem = Model.extend({
        source: 'shop',
        fn: 'shop_order_status',
        parse: function (data) {
            return data && data.shop;
        }
    });

    return TrackingSystem;

});