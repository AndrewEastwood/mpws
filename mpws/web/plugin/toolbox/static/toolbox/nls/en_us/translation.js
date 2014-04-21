define("plugin/toolbox/toolbox/nls/en_us/translation", [
    // here we will call default lang pkgs to override them
    'default/js/lib/underscore',
    'website/toolbox/nls/en_us/plugin_toolbox'
], function (_, CustomerShop) {
    return _.extend({}, {
        order_status_NEW: 'New',
        order_status_ACTIVE: 'In Progress',
        order_status_LOGISTIC_DELIVERING: 'Shipped',
        order_status_LOGISTIC_DELIVERED: 'Delivered',
        order_status_SHOP_CLOSED: 'Completed',
    }, CustomerShop);
});