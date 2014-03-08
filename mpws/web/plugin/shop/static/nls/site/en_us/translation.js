define("plugin/shop/nls/site/en_us/translation", [
    // here we will call default lang pkgs to override them
    'default/js/lib/underscore',
    'customer/nls/shop/site/en_us/translation'
], function (_, CustomerShop) {
    return _.extend({}, {
        order_status_NEW: 'New',
        order_status_ACTIVE: 'In Progress',
        order_status_LOGISTIC_DELIVERING: 'Shipped',
        order_status_LOGISTIC_DELIVERED: 'Delivered',
        order_status_SHOP_CLOSED: 'Completed',
    }, CustomerShop);
});