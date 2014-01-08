APP.Modules.register("plugin/shop/view/productListCatalog", [], [
    'lib/jquery',
    'lib/underscore',
    'view/mview',
    'lib/mpws.page',
    'plugin/shop/model/productListCatalog',
    /* js extensions */
    'lib/jquery.cookie',
    /* ui components */
    'lib/bootstrap',
    'lib/bootstrap-combobox',
    'lib/bootstrap-slider',
], function (app, Sandbox, $, _, MView, mpwsPage, modelProductListCatalog) {

    var ProductListCatalog = MView.extend({

        name: "shopProductListCatalog",

        model: new modelProductListCatalog(),

        template: 'plugin.shop.component.shopProductListCatalog@hbs',

        dependencies: {
            productEntryViewList: {
                url: "plugin.shop.component.productEntryViewList@hbs",
                type: mpwsPage.TYPE.PARTIAL
            },
            shopProductListFilter: {
                url: "plugin.shop.component.shopProductListFilter@hbs",
                type: mpwsPage.TYPE.PARTIAL
            },
            shopProductListPresentation: {
                url: "plugin.shop.component.shopProductListPresentation@hbs",
                type: mpwsPage.TYPE.PARTIAL
            },
            shopProductListSubCategories: {
                url: "plugin.shop.component.shopProductListSubCategories@hbs",
                type: mpwsPage.TYPE.PARTIAL
            },
        },

        initialize: function (options) {

            var _self = this;
            var _filterOptions = null;
            var _filterIsBusy = false;

            // extend parent
            MView.prototype.initialize.call(this, options);

            _self.on('mview:filter', function() {
                // do not apply filter when previous is still running
                if (_filterIsBusy)
                    return;

                _filterIsBusy = true;
                _self.applyFiltering(_filterOptions);
            })

            _self.on('mview:rendered', function () {

                _filterOptions = _(_self.model.getUrlData()).clone();
                var _data = _self.model.get('data');

                // update filter options
                _self.$el.find('#shopProductListFiltering_SortByID').val(_data.filterOptionsApplied.filter_viewSortBy);
                _self.$el.find('#shopProductListDisplayItems_DisplayCountID').val(_data.filterOptionsApplied.filter_viewItemsOnPage);

                // enchance ui components
                var _filterPrice = _self.$el.find('.slider').slider();
                var _filterDropdowns = _self.$el.find('.selectpicker').selectpicker();



                // filter dropdowns
                _filterDropdowns.on('change', function () {
                    // app.log($(this).data('name'),  $(this).val());
                    
                    _filterOptions.filter_viewSortBy = _self.$el.find('#shopProductListFiltering_SortByID').val();
                    _filterOptions.filter_viewItemsOnPage = _self.$el.find('#shopProductListDisplayItems_DisplayCountID').val();

                    $.cookie('filter_viewSortBy', _filterOptions.filter_viewSortBy);
                    $.cookie('filter_viewItemsOnPage', _filterOptions.filter_viewItemsOnPage);

                    _self.trigger('mview:filter');
                })
                
                // price range
                _filterPrice.on('slideStop', function(){
                    var _priceRange = $(this).data('value');
                    
                    _filterOptions.filter_commonPriceMin = _priceRange[0];
                    _filterOptions.filter_commonPriceMax = _priceRange[1];

                    $.cookie('filter_commonPriceMin', _filterOptions.filter_commonPriceMin);
                    $.cookie('filter_commonPriceMax', _filterOptions.filter_commonPriceMax);

                    _self.$el.find('.shop-filter-price-start').text(_filterOptions.filter_commonPriceMin);
                    _self.$el.find('.shop-filter-price-end').text(_filterOptions.filter_commonPriceMax);

                    _self.trigger('mview:filter');
                });

                // filter button
                _self.$el.find('.shop-apply-filter').on('click', function () {
                    _self.trigger('mview:filter');
                });

                _filterIsBusy = false;
            });
        },

        applyFiltering: function (filterOptions) {

            // prepare filter params
            // filterOptions

            // and then update url data to
            // get new list of products rendered
            // this.model.setUrlData();
            var _orig = _(this.model.getUrlData()).clone();

            // app.log(true, 'view shopProductListCatalog: applying new filterOptions', filterOptions);
            // app.log(true, 'view shopProductListCatalog: oring data', _orig);

            this.model.setUrlData(_.extend({}, this.model.getUrlData(), filterOptions));

        }

    });

    return ProductListCatalog;

});