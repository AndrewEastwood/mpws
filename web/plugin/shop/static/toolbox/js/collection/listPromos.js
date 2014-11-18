define('plugin/shop/toolbox/js/collection/listPromos', [
    'default/js/lib/underscore',
    'plugin/shop/toolbox/js/model/promo',
    'default/js/lib/backbone-paginator',
    'default/js/lib/cache'
], function (_, ModelOrigin, PageableCollection, Cache) {

    var ListPromos = PageableCollection.extend({

        model: ModelOrigin,

        url: function () {
            var urlOptions = {
                source: 'shop',
                fn: 'promos',
                expired: !!this.queryParams.expired
            };

            return APP.getApiLink(urlOptions);
        },

        // Initial pagination states
        state: {
            pageSize: 100,
            order: 1
        },

        initialize: function () {
            this.extras = {};
            // You can remap the query parameters from `state` keys from
            // the default to those your server supports
            this.queryParams = Cache.get('shopPromosListRD') || {
                totalPages: null,
                totalRecords: null,
                pageSize: "limit",
                sortKey: "sort"
            };
        },

        setCustomQueryField: function (field, value) {
            this.queryParams['_f' + field] = value;
            Cache.set('shopPromosListRD', this.queryParams);
            return this;
        },

        getCustomQueryField: function (field) {
            return this.queryParams["_f" + field];
        },

        setCustomQueryParam: function (param, value) {
            this.queryParams['_p' + param] = value;
            Cache.set('shopPromosListRD', this.queryParams);
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
            this.extras.withExpired = this.queryParams.expired;
            return resp.items;
        },

        fetchWithExpired: function (includeExpired, fetchOptions) {
            this.queryParams.expired = includeExpired;
            this.fetch(fetchOptions);
        }

    });

    return ListPromos;
});