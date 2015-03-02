define([
    'backbone',
    'underscore',
    'plugins/shop/toolbox/js/model/origin',
    'backbone-pageable'
], function (Backbone, _, ModelOrigin) {

    var ListOrigins = Backbone.PageableCollection.extend({

        model: ModelOrigin,

        url: APP.getApiLink('shop', 'origins'),

        // Initial pagination states
        state: {
            pageSize: 30,
            order: 1
        },

        initialize: function () {
            this.extras = {};
            // You can remap the query parameters from `state` keys from
            // the default to those your server supports
            this.queryParams = Cache.get('shopOriginsListRD') || {
                totalPages: null,
                totalRecords: null
            };
            this.queryParams.pageSize = "limit";
            this.queryParams.sortKey = "sort";
        },



        // You can remap the query parameters from `state` keys from
        // the default to those your server supports
        // queryParams: {
        //     totalPages: null,
        //     totalRecords: null,
        //     pageSize: "limit",
        //     sortKey: "sort"
        // },

        setCustomQueryField: function (field, value) {
            this.queryParams['_f' + field] = value;
            Cache.set('shopOriginsListRD', this.queryParams);
            return this;
        },

        getCustomQueryField: function (field) {
            return this.queryParams["_f" + field];
        },

        setCustomQueryParam: function (param, value) {
            this.queryParams['_p' + param] = value;
            Cache.set('shopOriginsListRD', this.queryParams);
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

    return ListOrigins;
});