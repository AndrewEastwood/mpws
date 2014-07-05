define("plugin/shop/site/js/view/listProductCatalog", [
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'plugin/shop/site/js/collection/listProductCatalog',
    'plugin/shop/site/js/view/productItemShort',
    'default/js/lib/bootstrap-dialog',
    'default/js/plugin/hbs!plugin/shop/site/hbs/productCatalog',
    'default/js/lib/bootstrap',
    'default/js/lib/bootstrap-combobox',
    'default/js/lib/bootstrap-slider',
    "default/js/lib/jquery.cookie"
], function (_, Backbone, CollListProductCatalog, ProductItemShort, dlg, tpl) {

    var ListProductCatalog = Backbone.View.extend({
        className: 'shop-product-list shop-product-list-catalog',
        template: tpl,
        events: {
            "change .selectpicker": 'filterProducts_Dropdowns',
            "change input[name^='filter_']": 'filterProducts_Other',
            "click a.list-group-item:not(.disabled)": 'filterProducts_ListItemClicked',
            "slideStop .slider": 'filterProducts_PriceChanged',
            "click .shop-filter-cancel": 'filterProducts_CancelFilter',
            "click .shop-load-more": 'filterProducts_LoadMore',
        },
        // getFilterOptions: function () {
        //     return ['filter_viewSortBy',
        //             'filter_viewPageNum',
        //             'filter_commonPriceMax',
        //             'filter_commonPriceMin',
        //             'filter_commonAvailability',
        //             'filter_commonOnSaleTypes',
        //             'filter_categoryBrands',
        //             'filter_viewItemsOnPage'];
        // },
        initialize: function (options) {
            // debugger;
            // this.defaultFilter = this.getDefaultFilter(true);
            // this.collection.updateUrl(this.defaultFilter);
            this.collection = new CollListProductCatalog(options.categoryID);
            this.collection.on('sync', this.render, this);
            this.collection.on('reset', this.render, this);
        },
        render: function () {
            // debugger;

            var self = this;
            var displayItems = [];
            var _filterData = this.collection.filter;

            this.collection.each(function(model){
                var productView = new ProductItemShort({model: model});
                displayItems.push(productView.render().el);
            });

            this.$el.html(this.template({
                displayItems: displayItems,
                filter: _filterData
            }));

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
            return this;
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

            this.collection.setFilter(_targetFilterName, _filterOptions[_targetFilterName]);

            this.collection.fetch();
            // this.fetchAndRender(_filterOptions);
        },
        filterProducts_Dropdowns: function (event) {
            // console.log(event);
            // debugger;
            var filter_viewSortBy = this.$('#shopProductListFiltering_SortByID').val();
            var filter_viewItemsOnPage = this.$('#shopProductListDisplayItems_DisplayCountID').val();

            this.collection.setFilter('filter_viewPageNum', 0);
            this.collection.setFilter('filter_viewSortBy', filter_viewSortBy);
            this.collection.setFilter('filter_viewItemsOnPage', filter_viewItemsOnPage);
            this.collection.fetch();
            /*
            this.fetchAndRender({
                filter_viewPageNum: 0,
                filter_viewSortBy: filter_viewSortBy,
                filter_viewItemsOnPage: filter_viewItemsOnPage
            });*/
        },
        filterProducts_PriceChanged: function (event) {
            // console.log(event);
            // debugger;
            var _priceRange = $(event.target).data('value');

            var filter_commonPriceMin = _priceRange[0];
            var filter_commonPriceMax = _priceRange[1];

            this.collection.setFilter('filter_commonPriceMin', filter_commonPriceMin);
            this.collection.setFilter('filter_commonPriceMax', filter_commonPriceMax);

            this.$('.shop-filter-price-start').text(filter_commonPriceMin);
            this.$('.shop-filter-price-end').text(filter_commonPriceMax);

            this.collection.fetch();
            // this.fetchAndRender({
            //     filter_viewPageNum: 0,
            //     filter_commonPriceMin: filter_commonPriceMin,
            //     filter_commonPriceMax: filter_commonPriceMax
            // });
        },
        filterProducts_CancelFilter: function () {
            // var self = this;
            // // this.defaultFilter = this.getDefaultFilter();
            // _(this.getFilterOptions()).each(function(filterKey){
            //     self.collection.setFilter(filterKey, "");
            // });
            // this.fetchAndRender(this.defaultFilter, {reset: true});
            this.collection.resetFilter().fetch({reset: true});
        },
        filterProducts_ListItemClicked: function () {
            var _innerCheckbox = $(event.target).find('input[type="checkbox"]');
            _innerCheckbox.prop('checked', !_innerCheckbox.prop('checked'));
            _innerCheckbox.trigger('change');
        },
        filterProducts_LoadMore: function () {
            var _filter_commonPriceMin = this.collection.getFilter('filter_commonPriceMin');
            _filter_commonPriceMin++;
            this.collection.setFilter('filter_commonPriceMin', _filter_commonPriceMin);
            // debugger;
            this.collection.fetch({update: true, remove: false});
            // this.fetchAndRender(_filterOptions, {update: true, remove: false});
        }
    });

    return ListProductCatalog;

});