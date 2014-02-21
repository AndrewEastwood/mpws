define('plugin/shop/js/collection/listProductCatalog', [
    'default/js/lib/underscore',
    'default/js/collection/mCollection',
    'plugin/shop/js/model/productItemBase',
    'default/js/lib/url',
    'plugin/shop/js/lib/utils',
    'default/js/lib/jquery.cookie',
], function (_, MCollection, ProductItemBase, JSUrl, ShopUtils) {

    var ListProductCatalog = MCollection.extend({
        defaultFilter: {

            filter_viewSortBy: null,

            filter_viewItemsOnPage: 3,

            filter_viewPageNum: 0,

            // common
            // these options are common for all existed categories
            // so we just keep them here and render them at very top
            // of the filter panel

            filter_commonPriceMax: null,

            filter_commonPriceMin: null,

            filter_commonAvailability: [],

            filter_commonOnSaleTypes: [],

            // category based (use specifications of current category)
            // these options have category specific options and they are
            // being rendered under the common options
            filter_categoryBrands: [],
        },
        model: ProductItemBase,
        initialize: function () {
            MCollection.prototype.initialize.call(this);
            // debugger;
            this.updateUrlOptions(_.extend({}, this.defaultFilter, {
                source: 'shop',
                fn: 'shop_catalog',
                // restore filter

                filter_viewSortBy: $.cookie('filter_viewSortBy') || null,

                filter_viewPageNum: $.cookie('filter_viewPageNum') || null,

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
            }));
        },
        parse: function (data) {
            // adjust products
            var products = ShopUtils.adjustProductEntry(data && data.shop);
            // adjust filtering
            var filter = data.shop.filter;
            var productItems = _(products).map(function(item){ return item; });

            // join category/origin info
            _(filter.filterOptionsAvailable.filter_categoryBrands).each(function(brand){
                // debugger;
                if (filter.filterOptionsApplied.filter_categoryBrands[brand.ID])
                    _.extend(brand, filter.filterOptionsApplied.filter_categoryBrands[brand.ID]);
                else
                    brand.ProductCount = 0;
            });
            _(filter.filterOptionsAvailable.filter_categorySubCategories).each(function(category){
                if (filter.filterOptionsApplied.filter_categorySubCategories[category.ID])
                    category.ProductCount = filter.filterOptionsApplied.filter_categorySubCategories[category.ID].ProductCount;
                else
                    category.ProductCount = 0;
            });

            // console.log(filter);
            // console.log(data.shop.info.count, productItems.length, this.length);

            // debugger;
            this.setExtras('filter', filter);
            this.setExtras('hasMoreProducts', data.shop.info.count > productItems.length + this.length);

            return productItems;
        }
    });

    return ListProductCatalog;
});