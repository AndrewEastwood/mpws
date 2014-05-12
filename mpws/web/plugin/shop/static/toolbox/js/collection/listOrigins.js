define('plugin/shop/toolbox/js/collection/listOrigins', [
    'default/js/lib/sandbox',
    'default/js/lib/underscore',
    'default/js/lib/backbone-pageable'
], function (Sandbox, _, PageableCollection) {

    var ListProducts = PageableCollection.extend({

        url: APP.getApiLink({
            source: 'shop',
            fn: 'shop_manage_origins',
            action: 'list'
        }),

        // Initial pagination states
        state: {
            pageSize: 1000,
            // sortKey: "updated",
            order: 1
        },

        // You can remap the query parameters from `state` keys from
        // the default to those your server supports
        queryParams: {
            totalPages: null,
            totalRecords: null,
            sortKey: "sort",
            // q: "state:closed repo:jashkenas/backbone"
        },

        // parseState: function (resp, queryParams, state, options) {
        //     var state = {
        //         totalRecords: parseInt(resp && resp.shop && resp.shop.total_count || 0, 10)
        //     };
        //     Sandbox.eventNotify('plugin:shop:productList:parseState', {collection: this, state: state});
        //     return state;
        // },

        parseRecords: function (resp) {
            var origins = resp && resp.shop && resp.shop.origins || [];
            return origins;
        }

    });

    return ListProducts;

});