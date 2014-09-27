define('plugin/shop/toolbox/js/collection/listCategories', [
    'default/js/lib/underscore',
    'plugin/shop/toolbox/js/model/category',
    'default/js/lib/backbone-paginator'
], function (_, ModelCategory, PageableCollection) {

    var ListCategories = PageableCollection.extend({

        model: ModelCategory,

        url: APP.getApiLink({
            source: 'shop',
            fn: 'categories'
        }),

        // Initial pagination states
        state: {
            pageSize: 30,
            order: 1
        },

        // You can remap the query parameters from `state` keys from
        // the default to those your server supports
        queryParams: {
            totalPages: null,
            totalRecords: null,
            pageSize: "limit",
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

        parseRecords: function (resp, options) {
            return resp.items;
        }

    });

    return ListCategories;

});