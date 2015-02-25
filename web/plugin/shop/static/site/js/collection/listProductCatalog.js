define('plugin/shop/site/js/collection/listProductCatalog', [
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'default/js/lib/cache',
    'plugin/shop/site/js/model/product',
    'plugin/shop/common/js/lib/utils',
    'default/js/lib/jquery.cookie',
], function (_, Backbone, Cache, ModelProduct, ShopUtils) {

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

                // filter_viewItemsOnPage: 3,

                filter_viewPageNum: 1,//reset ? null : this.restoreFilter('filter_viewPageNum') || 1,

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
                filter_commonFeatures: reset ? null : this.restoreFilter('filter_commonFeatures') ? this.restoreFilter('filter_commonFeatures') : null,
            };
        },
        resetFilter: function () {
            this.filter.filterOptionsApplied = this.createFilter(true);
            _(this.filter.filterOptionsApplied).each(function (v, filterKey) {
                Cache.set(filterKey, null);
            });
            return this;
        },
        isFilterApplied: function (filter) {
            // debugger
            if (!filter) {
                return false;
            }
            var isApplied = false;
            isApplied = isApplied || filter.filterOptionsApplied.filter_commonPriceMax != filter.filterOptionsAvailable.filter_commonPriceMax;
            isApplied = isApplied || filter.filterOptionsApplied.filter_commonPriceMin != filter.filterOptionsAvailable.filter_commonPriceMin;
            isApplied = isApplied || !_.isEmpty(filter.filterOptionsApplied.filter_viewSortBy);
            isApplied = isApplied || !_.isEmpty(filter.filterOptionsApplied.filter_commonFeatures);
            isApplied = isApplied || !_.isEmpty(filter.filterOptionsApplied.filter_commonStatus);
            isApplied = isApplied || !_.isEmpty(filter.filterOptionsApplied.filter_categoryBrands);
            // debugger
            return isApplied;
        },
        generateFilterStorageKey: function (filterKey) {
            return filterKey;
            // return Backbone.history.fragment.replace(/\//gi, '_') + '_' + filterKey;
        },
        restoreFilter: function (filterKey) {
            var key = this.generateFilterStorageKey(filterKey);
            return Cache.get(filterKey) || null;
            // return this.savedFilters[key] || null;
        },
        getFilter: function (filterKey) {
            return this.filter.filterOptionsApplied[filterKey];
        },
        setFilter: function (filterKey, value) {
            var key = this.generateFilterStorageKey(filterKey);
            this.filter.filterOptionsApplied[filterKey] = value;
            Cache.set(filterKey, this.filter.filterOptionsApplied[filterKey]);
            // this.savedFilters[key] = value;
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
            // console.log(_options);
            return APP.getApiLink(_.extend({}, _options, {
                source: 'shop',
                fn: 'catalog'}));
        },
        parse: function (data) {
            // adjust products
            // debugger;
            // adjust filtering
            var filter = data.filter;
            var productItems = _(data.items).map(function(item){ return item; });

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
            this._location = data._location;
            this.category = filter.info.category;
            // debugger;
            // this.filter.info.hasMoreProducts = filter.info.count > productItems.length + this.length;

            // pagination
            var pagintaion = {items:[]};
            pagintaion.current = this.filter.filterOptionsApplied.filter_viewPageNum;
            pagintaion.pages = Math.ceil(filter.info.count / this.filter.filterOptionsApplied.filter_viewItemsOnPage);
            var leftDelata = 0;
            var left = 1;
            if (pagintaion.current - 5 < 0) {
                leftDelata = Math.abs(pagintaion.current - 5);
            } else {
                left = pagintaion.current - 5 || 1;
            }
            var right = pagintaion.current + 5 + leftDelata;
            if (right > pagintaion.pages) {
                var rightDelta = right - pagintaion.pages;
                right = pagintaion.pages;
                var leftAdjustment = left - rightDelta + 1 || 1;
                if (leftAdjustment > 0) {
                    left -= rightDelta;
                }
                if (left <= 0) {
                    left = 1;
                }
            }
            for (var i = left; i <= right; i++) {
                pagintaion.items.push(i);
            }
            // debugger;
            this.pagintaion = pagintaion;
            this.filter.active = this.isFilterApplied(this.filter);
            // debugger;
            return productItems;
        }
    });

    return ListProductCatalog;
});