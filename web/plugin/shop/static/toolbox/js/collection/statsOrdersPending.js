define('plugin/shop/toolbox/js/collection/statsOrdersPending', [
    'plugin/shop/toolbox/js/collection/basicOrders'
], function (ListOrders) {

    var StatsListOrdersPending = ListOrders.extend({
        initialize: function () {
            ListOrders.prototype.initialize.apply(this);
            this.url = APP.getApiLink({
                source: 'shop',
                fn: 'stats',
                type: 'orders_list_pending'
            });
        }
    });

    return StatsListOrdersPending;
});