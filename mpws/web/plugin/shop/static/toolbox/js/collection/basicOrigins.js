define('plugin/shop/toolbox/js/collection/basicOrigins', [
    'default/js/lib/underscore',
    'plugin/shop/toolbox/js/model/origin',
    'default/js/lib/backbone-paginator'
], function (_, ModelOrigin, PageableCollection) {

    var ListOrigins = PageableCollection.extend({

        model: ModelOrigin,

        url: APP.getApiLink({
            source: 'shop',
            fn: 'origins',
            type: 'list'
        }),

        // Initial pagination states
        state: {
            pageSize: 5,
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
            return state;
        },

        parseRecords: function (resp) {
            return resp.items;
        }

    });

    return ListOrigins;
});