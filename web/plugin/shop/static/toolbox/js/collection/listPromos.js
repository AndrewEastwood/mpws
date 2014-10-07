define('plugin/shop/toolbox/js/collection/listPromos', [
    'default/js/lib/underscore',
    'plugin/shop/toolbox/js/model/promo',
    'default/js/lib/backbone-paginator',
    'default/js/lib/cache'
], function (_, ModelOrigin, PageableCollection, Cache) {

    var ListPromos = PageableCollection.extend({
        extras: {},

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

        // You can remap the query parameters from `state` keys from
        // the default to those your server supports
        queryParams: Cache.get('shopPromosListRD') || {
            totalPages: null,
            totalRecords: null,
            pageSize: "limit",
            sortKey: "sort"
        },

        fetch: function (options) {
            Cache.set('shopPromosListRD', this.queryParams);
            return Backbone.Collection.prototype.fetch.call(this, options);
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