define('plugin/shop/toolbox/js/collection/listPromos', [
    'default/js/lib/underscore',
    'plugin/shop/toolbox/js/model/promo',
    'default/js/lib/backbone-paginator'
], function (_, ModelOrigin, PageableCollection) {

    var ListPromos = PageableCollection.extend({

        model: ModelOrigin,

        url: APP.getApiLink({
            source: 'shop',
            fn: 'promocodes'
        }),

        // Initial pagination states
        state: {
            pageSize: 100,
            order: 1
        },

        // You can remap the query parameters from `state` keys from
        // the default to those your server supports
        queryParams: {
            totalPages: null,
            totalRecords: null,
            sortKey: "sort"
        },

        setCustomQueryField: function (field, value) {
            this.queryParams['_f' + field] = value;
            return this;
        },

        getCustomQueryField: function (field) {
            return this.queryParams["_f" + field];
        },

        setCustomQueryParam: function (param, value) {
            this.queryParams['_p' + param] = value;
            return this;
        },

        getCustomQueryParam: function (param) {
            return this.queryParams["_p" + param];
        },

        parseState: function (resp, queryParams, state, options) {
            var state = {
                totalRecords: parseInt(resp && resp.info.total_entries || 0, 10),
                currentPage: parseInt(resp && resp.info.page || 1, 10)
            };
            return state;
        },

        parseRecords: function (resp) {
            return resp.items;
        }

    });

    return ListPromos;
});