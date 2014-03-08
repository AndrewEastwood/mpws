define("plugin/shop/js/view/listProductCatalog", [
    'default/js/lib/underscore',
    'default/js/view/mView',
    'plugin/shop/js/collection/listProductCatalog',
    'plugin/shop/js/view/productItemShort',
    'default/js/lib/bootstrap-dialog',
    'default/js/plugin/hbs!plugin/shop/hbs/site/productCatalog',
    'default/js/lib/bootstrap',
    'default/js/lib/bootstrap-combobox',
    'default/js/lib/bootstrap-slider',
    "default/js/lib/jquery.cookie"
], function (_, MView, CollListProductCatalog, ProductItemShort, dlg, tpl) {

    // dlg.show({
    //     title: 'Say-hello dialog',
    //     message: 'Hi Apple!'
    // });

    var ListProductCatalog = MView.extend({
        // tagName: 'div',
        className: 'shop-product-list shop-product-list-catalog',
        itemViewClass: ProductItemShort,
        template: tpl,
        collection: new CollListProductCatalog(),
        events: {
            "change .selectpicker": 'filterProducts_Dropdowns',
            "change input[name^='filter_']": 'filterProducts_Other',
            "click a.list-group-item:not(.disabled)": 'filterProducts_ListItemClicked',
            "slideStop .slider": 'filterProducts_PriceChanged',
            "click .shop-filter-cancel": 'filterProducts_CancelFilter',
            "click .shop-load-more": 'filterProducts_LoadMore',
        },
        savedFilters: {},
        getFilterOptions: function () {
            return ['filter_viewSortBy',
                    'filter_viewPageNum',
                    'filter_commonPriceMax',
                    'filter_commonPriceMin',
                    'filter_commonAvailability',
                    'filter_commonOnSaleTypes',
                    'filter_categoryBrands',
                    'filter_viewItemsOnPage'];
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
        getDefaultFilter: function (restorePrevious) {
            return {

                categoryID: this.options.categoryID,

                filter_viewSortBy: restorePrevious && this.getOrSetFilter('filter_viewSortBy') || null,

                filter_viewItemsOnPage: 3,

                filter_viewPageNum: restorePrevious && this.getOrSetFilter('filter_viewPageNum') || null,

                // common
                // these options are common for all existed categories
                // so we just keep them here and render them at very top
                // of the filter panel

                filter_commonPriceMax: restorePrevious && this.getOrSetFilter('filter_commonPriceMax') || null,

                filter_commonPriceMin: restorePrevious && this.getOrSetFilter('filter_commonPriceMin') || null,

                filter_commonAvailability: restorePrevious && this.getOrSetFilter('filter_commonAvailability') ? this.getOrSetFilter('filter_commonAvailability').split(',') : [],

                filter_commonOnSaleTypes: restorePrevious && this.getOrSetFilter('filter_commonOnSaleTypes') ? this.getOrSetFilter('filter_commonOnSaleTypes').split(',') : [],

                // category based (use specifications of current category)
                // these options have category specific options and they are
                // being rendered under the common options
                filter_categoryBrands: restorePrevious && this.getOrSetFilter('filter_categoryBrands') ? this.getOrSetFilter('filter_categoryBrands').split(',') : [],
            };
        },
        initialize: function () {
            MView.prototype.initialize.call(this);

        // initialize: function () {
            // options = _.extend({categoryID: null}, options);

            // debugger;
            this.defaultFilter = this.getDefaultFilter(true);
            this.collection.updateUrl(this.defaultFilter);


            // return MView.prototype.fetchAndRender.call(this, _.extend({}, this.collection.defaultFilter, options), fetchOptions);
            
            this.on('mview:renderComplete', function () {

                var _filterData = this.collection.getExtras().filter;

                // update (restore) filter options by server applied filter
                this.$('#shopProductListFiltering_SortByID').val(_filterData.filterOptionsApplied.filter_viewSortBy);
                this.$('#shopProductListDisplayItems_DisplayCountID').val(_filterData.filterOptionsApplied.filter_viewItemsOnPage);
                this.$('input[name^="filter_"]').each(function(){
                    var _targetFilterName = $(this).attr('name');
                    var _targetFilterValue = $(this).val();
                    if (_(_filterData.filterOptionsApplied[_targetFilterName]).indexOf(_targetFilterValue) >= 0)
                        $(this).prop('checked', 'checked').attr('checked', 'checked');
                });

                // // enhance ui components
                // debugger;
                var _filterPrice = this.$('.slider').slider();
                var _filterDropdowns = this.$('.selectpicker').selectpicker();
            }, this);
        },
        // fetchAndRender: function (options, fetchOptions) {
        //     // debugger;
        //     debugger;
        //     // return MView.prototype.fetchAndRender.call(this, _.extend({}, this.collection.defaultFilter, options), fetchOptions);
        // },
        filterProducts_Other: function (event) {
            // console.log(event);
            // debugger;
            var _targetFilterName = $(event.target).attr('name');

            var _filterOptions = {
                filter_viewPageNum: 0
            };

            _filterOptions[_targetFilterName] = [];

            if ($(event.target).is(':checked'))
                _filterOptions[_targetFilterName].push($(event.target).val());
            else
                _filterOptions[_targetFilterName] = _.without(_filterOptions[_targetFilterName], $(event.target).val());

            this.getOrSetFilter(_targetFilterName, _filterOptions[_targetFilterName]);

            this.fetchAndRender(_filterOptions);
        },
        filterProducts_Dropdowns: function (event) {
            // console.log(event);
            // debugger;
            var filter_viewSortBy = this.$('#shopProductListFiltering_SortByID').val();
            var filter_viewItemsOnPage = this.$('#shopProductListDisplayItems_DisplayCountID').val();

            this.getOrSetFilter('filter_viewSortBy', filter_viewSortBy);
            this.getOrSetFilter('filter_viewItemsOnPage', filter_viewItemsOnPage);
            
            this.fetchAndRender({
                filter_viewPageNum: 0,
                filter_viewSortBy: filter_viewSortBy,
                filter_viewItemsOnPage: filter_viewItemsOnPage
            });
        },
        filterProducts_PriceChanged: function (event) {
            // console.log(event);
            // debugger;
            var _priceRange = $(event.target).data('value');

            var filter_commonPriceMin = _priceRange[0];
            var filter_commonPriceMax = _priceRange[1];

            this.getOrSetFilter('filter_commonPriceMin', filter_commonPriceMin);
            this.getOrSetFilter('filter_commonPriceMax', filter_commonPriceMax);

            this.$('.shop-filter-price-start').text(filter_commonPriceMin);
            this.$('.shop-filter-price-end').text(filter_commonPriceMax);

            this.fetchAndRender({
                filter_viewPageNum: 0,
                filter_commonPriceMin: filter_commonPriceMin,
                filter_commonPriceMax: filter_commonPriceMax
            });
        },
        filterProducts_CancelFilter: function () {
            var self = this;
            this.defaultFilter = this.getDefaultFilter();
            _(this.getFilterOptions()).each(function(filterKey){
                self.getOrSetFilter(filterKey, "");
            });
            this.fetchAndRender(this.defaultFilter, {reset: true});
        },
        filterProducts_ListItemClicked: function () {
            var _innerCheckbox = $(event.target).find('input[type="checkbox"]');
            _innerCheckbox.prop('checked', !_innerCheckbox.prop('checked'));
            _innerCheckbox.trigger('change');
        },
        filterProducts_LoadMore: function () {
            var _filterOptions = this.collection.getUrlOptions();
            _filterOptions.filter_viewPageNum++;
            // debugger;
            this.fetchAndRender(_filterOptions, {update: true, remove: false});
        }
    });

    return ListProductCatalog;

});