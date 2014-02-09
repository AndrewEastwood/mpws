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
            "slideStop .slider": 'filterProducts_PriceChanged',
        },
        initialize: function () {
            MView.prototype.initialize.call(this);
            this.on('mview:renderComplete', function () {
                debugger;
                this.delegateEvents();
                // enhance ui components
                var _filterPrice = this.$el.find('.slider').slider();
                var _filterDropdowns = this.$el.find('.selectpicker').selectpicker();
            }, this);
        },
        filterProducts_Other: function () {
            // debugger;
            var _targetFilterName = $(this).attr('name');

            var _filterOptions = {};

            if ($(this).is(':checked'))
                _filterOptions[_targetFilterName].push($(this).val());
            else
                _filterOptions[_targetFilterName] = _.without(_filterOptions[_targetFilterName], $(this).val());

            $.cookie(_targetFilterName, _filterOptions[_targetFilterName], {path: Backbone.history.fragment});

            this.fetchAndRender(_filterOptions);
        },
        filterProducts_Dropdowns: function () {
            // debugger;
            var filter_viewSortBy = this.$el.find('#shopProductListFiltering_SortByID').val();
            var filter_viewItemsOnPage = this.$el.find('#shopProductListDisplayItems_DisplayCountID').val();

            $.cookie('filter_viewSortBy', filter_viewSortBy, {path: Backbone.history.fragment});
            $.cookie('filter_viewItemsOnPage', filter_viewItemsOnPage, {path: Backbone.history.fragment});
            
            this.fetchAndRender({
                filter_viewSortBy: filter_viewSortBy,
                filter_viewItemsOnPage: filter_viewItemsOnPage
            });
        },
        filterProducts_PriceChanged: function () {
            // debugger;
            var _priceRange = $(this).data('value');

            var filter_commonPriceMin = _priceRange[0];
            var filter_commonPriceMax = _priceRange[1];

            $.cookie('filter_commonPriceMin', filter_commonPriceMin, {path: Backbone.history.fragment});
            $.cookie('filter_commonPriceMax', filter_commonPriceMax, {path: Backbone.history.fragment});

            this.$el.find('.shop-filter-price-start').text(filter_commonPriceMin);
            this.$el.find('.shop-filter-price-end').text(filter_commonPriceMax);

            this.fetchAndRender({
                filter_commonPriceMin: filter_commonPriceMin,
                filter_commonPriceMax: filter_commonPriceMax
            });
        },
    });

    return ListProductCatalog;

});