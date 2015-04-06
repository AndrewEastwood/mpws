define([
    'backbone',
    'underscore',
    'plugins/shop/toolbox/js/model/deliveryAgency',
    'backbone-pageable'
], function (Backbone, _, ModelDeliveryAgency) {

    var ListDeliveryAgencies = Backbone.PageableCollection.extend({

        model: ModelDeliveryAgency,

        url: APP.getApiLink('shop','delivery'),

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
            var info = resp && resp.info || {},
                state = {
                    totalRecords: parseInt(info.total_entries || 0, 10),
                    currentPage: parseInt(info.page || 1, 10)
                };
            return state;
        },

        parseRecords: function (resp, options) {
            return resp.items;
        }

    });

    return ListDeliveryAgencies;

});