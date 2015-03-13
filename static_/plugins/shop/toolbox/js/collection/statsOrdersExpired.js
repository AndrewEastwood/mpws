define([
    'plugins/shop/toolbox/js/collection/listOrders'
], function (ListOrders) {

    var StatsListOrdersExpired = ListOrders.extend({
        initialize: function () {
            ListOrders.prototype.initialize.apply(this);
            this.url = APP.getApiLink({
                source: 'shop',
                fn: 'shopstats',
                type: 'orders_list_expired'
            });
        }
    });

    return StatsListOrdersExpired;
});