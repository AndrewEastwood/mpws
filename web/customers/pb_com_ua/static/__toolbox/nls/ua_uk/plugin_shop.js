define("customer/nls/ua_uk/shop_toolbox", [
    // here we will call default lang pkgs to override them
    'default/js/lib/underscore',
    'customer/nls/ua_uk/shop_logistics',
    'customer/nls/ua_uk/shop_orders'
], function (_, CustomerShopLogistics, CustomerShopOrders) {
    return _.extend({}, CustomerShopLogistics, CustomerShopOrders);
});