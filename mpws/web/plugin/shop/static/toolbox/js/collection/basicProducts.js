define('plugin/shop/toolbox/js/collection/basicProducts', [
    'default/js/lib/sandbox',
    'default/js/lib/underscore',
    'plugin/shop/toolbox/js/model/product',
    'default/js/lib/backbone-pageable'
], function (Sandbox, _, ModelProduct, PageableCollection, lang) {

    var ListOrders = PageableCollection.extend({

        model: ModelProduct,

        url: APP.getApiLink({
            source: 'shop',
            fn: 'products'
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

        parseState: function (resp, queryParams, state, options) {
            var state = {
                totalRecords: parseInt(resp && resp.count || 0, 10)
            };
            return state;
        },

        parseRecords: function (resp, options) {
            // debugger;
            var _orders = resp.items;
            // _(_orders).map(function (orderEntry) {
            //     // debugger;
            //     // orderEntry.Status = [orderEntry.Status];
            //     // orderEntry.AccountFullName = orderEntry.account.FirstName + ' ' + orderEntry.account.LastName;
            //     // orderEntry.AccountPhone = orderEntry.account.Phone;
            //     // orderEntry.InfoTotal = orderEntry.info.total;
            //     // orderEntry.HasPromo = !!orderEntry.promo;
            //     // orderEntry.Discount = orderEntry.promo && orderEntry.promo.Discount || 0;
            //     return orderEntry;
            // });
            return _orders;
        }

    });

    return ListOrders;

});