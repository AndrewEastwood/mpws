define('plugin/shop/js/collection/listProductCatalog', [
    'default/js/lib/underscore',
    'default/js/collection/mCollection',
    'plugin/shop/js/model/productItemBase',
    'default/js/lib/url',
    'plugin/shop/js/lib/utils',
    'default/js/lib/jquery.cookie'
], function (_, MCollection, ProductItemBase, JSUrl, ShopUtils) {

    var ListProductCatalog = MCollection.extend({
        model: ProductItemBase,
        initialize: function () {
            // debugger;
            this.updateUrlOptions({
                source: 'shop',
                fn: 'shop_catalog'
            });
        },
        fetch: function (options) {

            // restore page filter
            this.updateUrlOptions({
                filter_viewSortBy: $.cookie('filter_viewSortBy') || null,

                filter_viewItemsOnPage: $.cookie('filter_viewItemsOnPage') || null,

                // common
                // these options are common for all existed categories
                // so we just keep them here and render them at very top
                // of the filter panel

                filter_commonPriceMax: $.cookie('filter_commonPriceMax') || null,

                filter_commonPriceMin: $.cookie('filter_commonPriceMin') || null,

                filter_commonAvailability: $.cookie('filter_commonAvailability') ? $.cookie('filter_commonAvailability').split(',') : [],

                filter_commonOnSaleTypes: $.cookie('filter_commonOnSaleTypes') ? $.cookie('filter_commonOnSaleTypes').split(',') : [],

                // category based (use specifications of current category)
                // these options have category specific options and they are
                // being rendered under the common options

                filter_categoryBrands: $.cookie('filter_categoryBrands') ? $.cookie('filter_categoryBrands').split(',') : [],
            });

            // debugger;
            MCollection.prototype.fetch.call(this, options);
        },
        parse: function (data) {
            // debugger;
            var products = ShopUtils.adjustProductEntry(data && data.shop);
            return _(products).map(function(item){ return item; });
        }
    });

    return ListProductCatalog;
});