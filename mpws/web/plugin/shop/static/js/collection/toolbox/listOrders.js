define('plugin/shop/js/collection/toolbox/listOrders', [
    'default/js/lib/sandbox',
    'customer/js/site',
    'default/js/lib/underscore',
    'default/js/lib/backbone-pageable',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/nls/toolbox',
], function (Sandbox, Site, _, PageableCollection, lang) {

    var ListOrders = PageableCollection.extend({

        url: Site.getApiLink('shop', 'shop_managed_orders_list'),

        // Initial pagination states
        state: {
            pageSize: 10,
            // sortKey: "updated",
            order: 1
        },

        // You can remap the query parameters from `state` keys from
        // the default to those your server supports
        queryParams: {
            totalPages: null,
            totalRecords: null,
            sortKey: "sort"
        },

        parseState: function (resp, queryParams, state, options) {
            var state = {
                totalRecords: parseInt(resp && resp.shop && resp.shop.total_count || 0, 10)
            };
            Sandbox.eventNotify('plugin:shop:orderList:parseState', {collection: this, state: state});
            return state;
        },

        parseRecords: function (resp, options) {
            // debugger;
            var _orders = resp.shop.orders;
            // var _statuses = [
            //     _(resp.shop.statuses).map(function(status){
            //         return [status, lang["order_status_" + status] || status];
            //     })
            // ];
            // transform order status to render it as select control
            _(_orders).map(function (orderEntry) {
                // debugger;
                orderEntry.Status = [orderEntry.Status];
                return orderEntry;
            });
            // debugger;
            // Sandbox.eventNotify('plugin:shop:orderList:dataReceived', );
            // Sandbox.eventNotify('plugin:shop:orderList:dataReceived', resp.shop);
            return _orders;
        }

    });

    return ListOrders;

});