define('plugin/shop/toolbox/js/collection/basicOrders', [
    'default/js/lib/sandbox',
    'default/js/lib/underscore',
    'plugin/shop/toolbox/js/model/order',
    'default/js/lib/backbone-paginator'
], function (Sandbox, _, ModelOrder, PageableCollection, lang) {

    var ListOrders = PageableCollection.extend({

        model: ModelOrder,

        url: APP.getApiLink({
            source: 'shop',
            fn: 'orders'
        }),

        // Initial pagination states
        state: {
            pageSize: 10,
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
            // debugger;
            var state = {
                totalRecords: parseInt(resp && resp.count || 0, 10)
            };

            return state;
        },

        parseRecords: function (resp, options) {
            // debugger;
            var _orders = resp.items;
            _(_orders).map(function (orderEntry) {
                // debugger;
                orderEntry.AccountFullName = orderEntry.account.FirstName + ' ' + orderEntry.account.LastName;
                orderEntry.AccountPhone = orderEntry.account.Phone;
                orderEntry.InfoTotal = orderEntry.info.total;
                orderEntry.HasPromo = !!orderEntry.promo;
                orderEntry.Discount = orderEntry.promo && orderEntry.promo.Discount || 0;
                return orderEntry;
            });
            return _orders;
        }

    });

    return ListOrders;

});