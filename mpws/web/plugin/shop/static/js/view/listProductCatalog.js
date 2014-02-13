define("plugin/shop/js/view/listProductCatalog", [
    'default/js/lib/underscore',
    'default/js/view/mView',
    'plugin/shop/js/collection/listProductCatalog',
    'plugin/shop/js/view/productItemShort',
    'default/js/plugin/hbs!plugin/shop/hbs/productCatalog',
    'default/js/lib/bootstrap',
    'default/js/lib/bootstrap-combobox',
    'default/js/lib/bootstrap-slider',
], function (_, MView, CollListProductCatalog, ProductItemShort, tpl) {

    var ListProductCatalog = MView.extend({
        tagName: 'div',
        className: 'shop-product-list shop-product-list-catalog',
        collection: new CollListProductCatalog(),
        itemViewClass: ProductItemShort,
        template: tpl,
        events: {
            "change .selectpicker": 'filterProducts_Dropdowns',
            "change input[name^='filter_']": 'filterProducts_Other',
            "click a.list-group-item": 'filterProducts_ListItemClicked',
            "slideStop .slider": 'filterProducts_PriceChanged',
            "click .shop-filter-cancel": 'filterProducts_CancelFilter',
        },
        initialize: function () {
            MView.prototype.initialize.call(this);
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
                var _filterPrice = this.$('.slider').slider();
                var _filterDropdowns = this.$('.selectpicker').selectpicker();

            }, this);
        },
        filterProducts_Other: function (event) {
            // console.log(event);
            // debugger;
            var _targetFilterName = $(event.target).attr('name');

            var _filterOptions = {};

            _filterOptions[_targetFilterName] = [];

            if ($(event.target).is(':checked'))
                _filterOptions[_targetFilterName].push($(event.target).val());
            else
                _filterOptions[_targetFilterName] = _.without(_filterOptions[_targetFilterName], $(event.target).val());

            $.cookie(_targetFilterName, _filterOptions[_targetFilterName], {path: Backbone.history.fragment});

            this.fetchAndRender(_filterOptions);
        },
        filterProducts_Dropdowns: function (event) {
            // console.log(event);
            // debugger;
            var filter_viewSortBy = this.$('#shopProductListFiltering_SortByID').val();
            var filter_viewItemsOnPage = this.$('#shopProductListDisplayItems_DisplayCountID').val();

            $.cookie('filter_viewSortBy', filter_viewSortBy, {path: Backbone.history.fragment});
            $.cookie('filter_viewItemsOnPage', filter_viewItemsOnPage, {path: Backbone.history.fragment});
            
            this.fetchAndRender({
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

            $.cookie('filter_commonPriceMin', filter_commonPriceMin, {path: Backbone.history.fragment});
            $.cookie('filter_commonPriceMax', filter_commonPriceMax, {path: Backbone.history.fragment});

            this.$('.shop-filter-price-start').text(filter_commonPriceMin);
            this.$('.shop-filter-price-end').text(filter_commonPriceMax);

            this.fetchAndRender({
                filter_commonPriceMin: filter_commonPriceMin,
                filter_commonPriceMax: filter_commonPriceMax
            });
        },
        filterProducts_CancelFilter: function () {
            this.fetchAndRender(this.collection.defaultFilter);
        },
        filterProducts_ListItemClicked: function () {
            var _innerCheckbox = $(event.target).find('input[type="checkbox"]');
            _innerCheckbox.prop('checked', !_innerCheckbox.prop('checked'));
            _innerCheckbox.trigger('change');
        }
    });

    return ListProductCatalog;

});