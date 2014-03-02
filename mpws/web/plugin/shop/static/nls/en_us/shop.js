define("plugin/shop/nls/en_us/shop", [
    // here we will call default lang pkgs to override them
    'default/js/lib/underscore',
    'default/js/plugin/i18n!customer/nls/shop'
], function (_, CustomerShop) {
    return _.extend({}, {
    	order_status_NEW: 'New',
    	order_status_ACTIVE: 'In Progress',
    	order_status_LOGISTIC_DELIVERING: 'Shipped',
    	order_status_LOGISTIC_DELIVERED: 'Delivered',
    	order_status_SHOP_CLOSED: 'Completed',
    }, CustomerShop);
});