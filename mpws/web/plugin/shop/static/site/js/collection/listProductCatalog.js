define('plugin/shop/site/js/collection/listProductCatalog', [
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'plugin/shop/site/js/model/product',
    'plugin/shop/common/js/lib/utils',
    'default/js/lib/jquery.cookie',
], function (_, Backbone, ModelProduct, ShopUtils) {

    var ListProductCatalog = Backbone.Collection.extend({
        model: ModelProduct,
        savedFilters: {},
        getFilterOptions: function () {
            return _(this.getRequestFilter()).keys();
        },
        getAppliedFilter: function () {
            return this.filter.filterOptionsApplied;
        },
        getAvailableFilter: function () {
            return this.filter.filterOptionsAvailable;
        },
        getRequestFilter: function (reset) {
            return {

                categoryID: this.options.categoryID,

                filter_viewSortBy: reset ? null : this.getOrSetFilter('filter_viewSortBy') || null,

                filter_viewItemsOnPage: 3,

                filter_viewPageNum: reset ? null : this.getOrSetFilter('filter_viewPageNum') || null,

                // common
                // these options are common for all existed categories
                // so we just keep them here and render them at very top
                // of the filter panel
                filter_commonPriceMax: reset ? null : this.getOrSetFilter('filter_commonPriceMax') || null,

                filter_commonPriceMin: reset ? null : this.getOrSetFilter('filter_commonPriceMin') || null,

                filter_commonAvailability: reset ? [] : this.getOrSetFilter('filter_commonAvailability') ? this.getOrSetFilter('filter_commonAvailability').split(',') : [],

                filter_commonOnSaleTypes: reset ? [] : this.getOrSetFilter('filter_commonOnSaleTypes') ? this.getOrSetFilter('filter_commonOnSaleTypes').split(',') : [],

                // category based (use specifications of current category)
                // these options have category specific options and they are
                // being rendered under the common options
                filter_categoryBrands: reset ? [] : this.getOrSetFilter('filter_categoryBrands') ? this.getOrSetFilter('filter_categoryBrands').split(',') : [],
            };
        },
        getOrSetFilter: function (filterKey, value) {
            var key = Backbone.history.fragment.replace(/\//gi, '_') + '_' + filterKey;
            var rez = null;
            // $.cookie.raw = true;
            if (_.isUndefined(value))
                rez = this.savedFilters[key] || "";// rez = $.cookie(key);
            else
                this.savedFilters[key] = value;//$.cookie(key, value);
            // $.cookie.json = false;
            return "" + rez;
        },
        url: function () {
            return APP.getApiLink(_.extend({
                source: 'shop',
                fn: 'catalog',
                type: 'browse'}, this.getRequestFilter()));
        },
        applyFilter: function (filterOptions) {

        },
        parse: function (data) {
            // adjust products
            debugger;
            // adjust filtering
            var filter = data.filter;
            var productItems = _(data.items).map(function(item){ return item; });

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
            this.filter = filter;
            this.hasMoreProducts = data.info.count > productItems.length + this.length;

            return productItems;
        }
    });

    return ListProductCatalog;
});