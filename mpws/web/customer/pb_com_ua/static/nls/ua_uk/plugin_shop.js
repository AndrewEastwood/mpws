define("website/nls/ua_uk/plugin_shop", [
    // here we will call default lang pkgs to override them
    'default/js/lib/underscore',
    'website/nls/ua_uk/plugin_shop_logistics',
    'website/nls/ua_uk/plugin_shop_orders'
], function (_, CustomerShopLogistics, CustomerShopOrders) {
    return _.extend({}, CustomerShopLogistics, CustomerShopOrders);
});