define([
    'plugins/shop/toolbox/js/collection/listOrders'
], function (ListOrders) {

    var StatsListOrdersPending = ListOrders.extend({
        initialize: function () {
            ListOrders.prototype.initialize.apply(this);
            this.url = APP.getApiLink('shop','shopstats',{
                type: 'orders_list_pending'
            });
        }
    });

    return StatsListOrdersPending;
});