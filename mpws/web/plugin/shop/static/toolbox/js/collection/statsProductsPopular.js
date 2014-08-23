define('plugin/shop/toolbox/js/collection/statsListProductsPopular', [
    'plugin/shop/toolbox/js/collection/listOrders'
], function (ListOrders) {

    var StatsListOrdersTodays = ListOrders.extend({
        initialize: function () {
            ListOrders.prototype.initialize.apply(this);
            this.url = APP.getApiLink({
                source: 'shop',
                fn: 'stats',
                type: 'orders_list_todays'
            });
        }
    });

    return StatsListOrdersTodays;
});