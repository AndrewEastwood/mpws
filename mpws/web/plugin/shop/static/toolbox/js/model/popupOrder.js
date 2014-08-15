define('plugin/shop/toolbox/js/model/popupOrder', [
    'default/js/lib/backbone',
    'plugin/shop/common/js/lib/utils'
], function (MModel, ShopUtils) {

    var PopupOrder = Backbone.Model.extend({
        idAttribute: "ID",
        url: APP.getApiLink({
            source: 'shop',
            fn: 'order'
        })//,
        // parse: function (data) {
        //     if (data.boughts)
        //         data.boughts = ShopUtils.adjustProductItem({products: data.boughts});
        //     return _data;
        // }
    });

    return PopupOrder;

});