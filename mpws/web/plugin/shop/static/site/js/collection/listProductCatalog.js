define('plugin/shop/site/js/collection/listProductCatalog', [
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'plugin/shop/site/js/model/product',
    'plugin/shop/common/js/lib/utils',
    'default/js/lib/jquery.cookie',
], function (_, Backbone, ModelProduct, ShopUtils) {

    var ListProductCatalog = Backbone.Collection.extend({
        model: ModelProduct,
        filter: {
            filterOptionsAvailable: {},
            filterOptionsApplied: {},
            info: {}
        },
        savedFilters: {},
        initialize: function (categoryID) {
            this.categoryID = categoryID;
            this.filter.filterOptionsApplied = this.createFilter(false);
        },
        createFilter: function (reset) {
            return {

                id: this.categoryID,

                filter_viewSortBy: reset ? null : this.restoreFilter('filter_viewSortBy') || null,

                filter_viewItemsOnPage: 3,

                filter_viewPageNum: reset ? null : this.restoreFilter('filter_viewPageNum') || 1,

                // common
                // these options are common for all existed categories
                // so we just keep them here and render them at very top
                // of the filter panel
                filter_commonPriceMax: reset ? null : this.restoreFilter('filter_commonPriceMax') || null,

                filter_commonPriceMin: reset ? null : this.restoreFilter('filter_commonPriceMin') || 0,

                filter_commonStatus: reset ? null : this.restoreFilter('filter_commonStatus') ? this.restoreFilter('filter_commonStatus') : null,

                // category based (use specifications of current category)
                // these options have category specific options and they are
                // being rendered under the common options
                filter_categoryBrands: reset ? null : this.restoreFilter('filter_categoryBrands') ? this.restoreFilter('filter_categoryBrands') : null,
                filter_categorySpecifications: reset ? null : this.restoreFilter('filter_categorySpecifications') ? this.restoreFilter('filter_categorySpecifications') : null,
            };
        },
        resetFilter: function () {
            this.filter.filterOptionsApplied = this.createFilter(true);
            return this;
        },
        generateFilterStorageKey: function (filterKey) {
            return Backbone.history.fragment.replace(/\//gi, '_') + '_' + filterKey;
        },
        restoreFilter: function (filterKey) {
            var key = this.generateFilterStorageKey(filterKey);
            return this.savedFilters[key] || null;
        },
        getFilter: function (filterKey) {
            return this.filter.filterOptionsApplied[filterKey];
        },
        setFilter: function (filterKey, value) {
            var key = this.generateFilterStorageKey(filterKey);
            this.filter.filterOptionsApplied[filterKey] = value;
            this.savedFilters[key] = value;
        },
        url: function () {
            var _options = {};
            // debugger;
            _(this.filter.filterOptionsApplied).each(function(item, key){
                if (_.isEmpty(item))
                    return;
                if (_.isArray(item))
                    _options[key] = item.join(',');
                else
                    _options[key] = item;
            });
            console.log(_options);
            return APP.getApiLink(_.extend({
                source: 'shop',
                fn: 'catalog',
                type: 'browse'}, _options));
        },
        parse: function (data) {
            // adjust products
            // debugger;
            // adjust filtering
            var filter = data.browse.filter;
            var productItems = _(data.browse.items).map(function(item){ return item; });

            // join category/origin info
            // _(filter.filterOptionsAvailable.filter_categoryBrands).each(function(brand){
            //     // debugger;
            //     if (filter.filterOptionsApplied.filter_categoryBrands[brand.ID])
            //         _.extend(brand, filter.filterOptionsApplied.filter_categoryBrands[brand.ID]);
            //     else
            //         brand.ProductCount = 0;
            // });
            // _(filter.filterOptionsAvailable.filter_categorySubCategories).each(function(category){
            //     if (filter.filterOptionsApplied.filter_categorySubCategories[category.ID])
            //         category.ProductCount = filter.filterOptionsApplied.filter_categorySubCategories[category.ID].ProductCount;
            //     else
            //         category.ProductCount = 0;
            // });

            // console.log(filter);
            // console.log(data.shop.info.count, productItems.length, this.length);

            // debugger;
            this.filter = filter;
            // debugger;
            this.filter.info.hasMoreProducts = filter.info.count > productItems.length + this.length;

            // debugger;
            return productItems;
        }
    });

    return ListProductCatalog;
});