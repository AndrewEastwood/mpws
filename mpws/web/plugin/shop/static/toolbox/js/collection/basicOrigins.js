define('plugin/shop/toolbox/js/collection/basicOrigins', [
    'default/js/lib/sandbox',
    'default/js/lib/underscore',
    'default/js/lib/backbone-paginator'
], function (Sandbox, _, PageableCollection) {

    var ListProducts = PageableCollection.extend({

        url: APP.getApiLink({
            source: 'shop',
            fn: 'shop_manage_origins',
            // action: 'list'
        }),

        // Initial pagination states
        state: {
            pageSize: 15,
            // sortKey: "updated",
            order: 1
        },

        mode: "client",

        // You can remap the query parameters from `state` keys from
        // the default to those your server supports
        queryParams: {
            totalPages: null,
            totalRecords: null,
            sortKey: "sort",
            // q: "state:closed repo:jashkenas/backbone"
        },

        setCustomQueryField: function (field, value) {
            this.queryParams['_f' + field] = value;
        },

        getCustomQueryField: function (field) {
            return this.queryParams["_f" + field];
        },

        setCustomQueryParam: function (param, value) {
            this.queryParams['_p' + param] = value;
        },

        getCustomQueryParam: function (param) {
            return this.queryParams["_p" + param];
        },

        parseState: function (resp, queryParams, state, options) {
            var state = {
                totalRecords: parseInt(resp && resp.info.total_entries || 0, 10),
                currentPage: parseInt(resp && resp.info.page || 1, 10)
            };
            Sandbox.eventNotify('plugin:shop:originList:parseState', {collection: this, state: state});
            return state;
        },

        parseRecords: function (resp) {
            var origins = resp && resp.items || [];
            return origins;
        }

    });

    return ListProducts;

});