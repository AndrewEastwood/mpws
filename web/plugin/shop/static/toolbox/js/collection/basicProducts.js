define('plugin/shop/toolbox/js/collection/basicProducts', [
    'default/js/lib/underscore',
    'plugin/shop/toolbox/js/model/product',
    'default/js/lib/backbone-paginator',
    'default/js/lib/cache'
], function (_, ModelProduct, PageableCollection, Cache) {

    var ListProducts = PageableCollection.extend({

        model: ModelProduct,

        url: function () {
            var url = APP.getApiLink({
                source: 'shop',
                fn: 'products'
            });

            return url;
        },

        extras: {},

        // Initial pagination states
        state: {
            pageSize: 30,
            order: 1
        },

        // You can remap the query parameters from `state` keys from
        // the default to those your server supports
        queryParams: Cache.get('shopProductsListRD') || {
            totalPages: null,
            totalRecords: null,
            sortKey: "sort"
        },

        fetch: function (options) {
            Cache.set('shopProductsListRD', this.queryParams);
            return PageableCollection.prototype.fetch.call(this, options);
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
                totalRecords: 0,
                currentPage: 1
            };

            if (resp) {
                if (resp.info) {
                    state.totalRecords = parseInt(resp.items.length, 10) || 0;
                    state.currentPage = parseInt(resp.info.page, 10) || 1;
                }
                this.extras._category = resp._category || null;
            }
            return state;
        },

        parseRecords: function (resp, options) {
            return resp.items;
        }

    });

    return ListProducts;

});