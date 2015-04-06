define([
    'backbone',
    'underscore',
    'plugins/shop/toolbox/js/model/promo',
    'cachejs',
    'backbone-pageable'
], function (Backbone, _, ModelOrigin, Cache) {

    var ListPromos = Backbone.PageableCollection.extend({

        model: ModelOrigin,

        url: function () {
            var urlOptions = {};
            if (this.queryParams.expired) {
                urlOptions.expired = true;
            }
            return APP.getApiLink('shop','promos',urlOptions);
        },

        // Initial pagination states
        state: {
            pageSize: 100,
            order: 1
        },

        initialize: function () {
            this.extras = {};
            // debugger
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
            var info = resp && resp.info || {},
                state = {
                    totalRecords: parseInt(info.total_entries || 0, 10),
                    currentPage: parseInt(info.page || 1, 10)
                };
            return state;
        },

        parseRecords: function (resp) {
            // debugger
            this.extras.withExpired = this.queryParams.expired;
            return resp.items;
        },

        fetchWithExpired: function (includeExpired, fetchOptions) {
            // debugger
            // debugger
            if (includeExpired)
                this.queryParams.expired = includeExpired;
            else
                delete this.queryParams.expired;
            Cache.set('shopPromosListRD', this.queryParams);
            this.fetch(fetchOptions);
        }

    });

    return ListPromos;
});