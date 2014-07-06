define("plugin/shop/site/js/view/listProductCatalog", [
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'plugin/shop/site/js/collection/listProductCatalog',
    'plugin/shop/site/js/view/productItemShort',
    'default/js/lib/bootstrap-dialog',
    'default/js/plugin/hbs!plugin/shop/site/hbs/productCatalog',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/site/nls/translation',
    'default/js/lib/bootstrap',
    'default/js/lib/bootstrap-combobox',
    'default/js/lib/bootstrap-slider',
    "default/js/lib/jquery.cookie"
], function (_, Backbone, CollListProductCatalog, ProductItemShort, dlg, tpl, lang) {

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
        initialize: function (options) {
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

            // debugger;
            this.$el.html(this.template({
                displayItems: displayItems,
                filter: _filterData,
                lang: lang
            }));

            // update (restore) filter options by server applied filter
            // this.$('#shopProductListFiltering_SortByID').val(_filterData.filterOptionsApplied.filter_viewSortBy);
            // this.$('#shopProductListDisplayItems_DisplayCountID').val(_filterData.filterOptionsApplied.filter_viewItemsOnPage);
            // this.$('input[name^="filter_"]').each(function(){
            //     // debugger;
            //     var _targetFilterName = $(this).attr('name');
            //     var _targetFilterValue = $(this).val();
            //     if (_(_filterData.filterOptionsApplied[_targetFilterName]).indexOf(_targetFilterValue) >= 0)
            //         $(this).prop('checked', 'checked').attr('checked', 'checked');
            // });

            // // enhance ui components
            // debugger;
            var _filterPrice = this.$('.slider').slider();
            var _filterDropdowns = this.$('.selectpicker').selectpicker();
            return this;
        },
        filterProducts_Other: function (event) {
            // console.log(event);
            // debugger;
            var _targetFilterName = $(event.target).attr('name');

            var _filterOptions = {
                filter_viewPageNum: 0
            };

            _filterOptions[_targetFilterName] = this.collection.getFilter(_targetFilterName);
            // debugger;

            if ($(event.target).is(':checked'))
                _filterOptions[_targetFilterName].push($(event.target).val());
            else
                _filterOptions[_targetFilterName] = _.without(_filterOptions[_targetFilterName], $(event.target).val());

            this.collection.setFilter('filter_viewPageNum', 0);
            this.collection.setFilter(_targetFilterName, _filterOptions[_targetFilterName]);

            this.collection.fetch();
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
        },
        filterProducts_PriceChanged: function (event) {
            // console.log(event);
            // debugger;
            var _priceRange = $(event.target).data('value');

            var filter_commonPriceMin = _priceRange[0];
            var filter_commonPriceMax = _priceRange[1];

            this.collection.setFilter('filter_viewPageNum', 0);
            this.collection.setFilter('filter_commonPriceMin', filter_commonPriceMin);
            this.collection.setFilter('filter_commonPriceMax', filter_commonPriceMax);

            this.$('.shop-filter-price-start').text(filter_commonPriceMin);
            this.$('.shop-filter-price-end').text(filter_commonPriceMax);

            this.collection.fetch();
        },
        filterProducts_CancelFilter: function () {
            this.collection.resetFilter().fetch({reset: true});
        },
        filterProducts_ListItemClicked: function (event) {
            var $el = $(event.target);
            var _innerCheckbox = $el.find('input[type="checkbox"]');
            _innerCheckbox.prop('checked', !_innerCheckbox.prop('checked'));
            _innerCheckbox.trigger('change');
            if (event && event.stopPropagation)
                event.stopPropagation();
            if ($el.parents('a').attr('rel') !== 'category' && $el.attr('rel') !== 'category')
                return false;
        },
        filterProducts_LoadMore: function () {
            var _filter_viewPageNum = this.collection.getFilter('filter_viewPageNum');
            _filter_viewPageNum++;
            this.collection.setFilter('filter_viewPageNum', _filter_viewPageNum);
            // debugger;
            this.collection.fetch({update: true, remove: false});
        }
    });

    return ListProductCatalog;

});