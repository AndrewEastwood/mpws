APP.Modules.register("plugin/shop/view/productListCatalog", [], [
    'lib/jquery',
    'lib/underscore',
    'lib/backbone',
    'view/mview',
    'lib/mpws.page',
    'plugin/shop/model/productListCatalog',
    /* js extensions */
    'lib/jquery.cookie',
    /* ui components */
    'lib/bootstrap',
    'lib/bootstrap-combobox',
    'lib/bootstrap-slider',
], function (app, Sandbox, $, _, Backbone, MView, mpwsPage, modelProductListCatalog) {

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
            });

            _self.on('mview:rendered', function () {

                _filterOptions = JSON.parse(JSON.stringify(_self.model.getUrlData()));
                var _data = _self.model.get('data');

                // update (restore) filter options by server applied filter
                _self.$el.find('#shopProductListFiltering_SortByID').val(_data.filterOptionsApplied.filter_viewSortBy);
                _self.$el.find('#shopProductListDisplayItems_DisplayCountID').val(_data.filterOptionsApplied.filter_viewItemsOnPage);
                _(_data.filterOptionsApplied.filter_categoryBrands).each(function (brandID){
                    var _brandItem = _self.$el.find('input[name="shopFilterBrand"][value="' + brandID + '"]');
                    if (_brandItem.length)
                        _brandItem.prop('checked', 'checked').attr('checked', 'checked');
                });

                // enhance ui components
                var _filterPrice = _self.$el.find('.slider').slider();
                var _filterDropdowns = _self.$el.find('.selectpicker').selectpicker();
                
                // filter dropdowns
                _filterDropdowns.on('change', function () {
                    // app.log($(this).data('name'),  $(this).val());
                    
                    _filterOptions.filter_viewSortBy = _self.$el.find('#shopProductListFiltering_SortByID').val();
                    _filterOptions.filter_viewItemsOnPage = _self.$el.find('#shopProductListDisplayItems_DisplayCountID').val();

                    $.cookie('filter_viewSortBy', _filterOptions.filter_viewSortBy, {path: Backbone.history.fragment});
                    $.cookie('filter_viewItemsOnPage', _filterOptions.filter_viewItemsOnPage, {path: Backbone.history.fragment});

                    _self.trigger('mview:filter');
                })
                
                // price range
                _filterPrice.on('slideStop', function(){
                    var _priceRange = $(this).data('value');

                    _filterOptions.filter_commonPriceMin = _priceRange[0];
                    _filterOptions.filter_commonPriceMax = _priceRange[1];

                    $.cookie('filter_commonPriceMin', _filterOptions.filter_commonPriceMin, {path: Backbone.history.fragment});
                    $.cookie('filter_commonPriceMax', _filterOptions.filter_commonPriceMax, {path: Backbone.history.fragment});

                    _self.$el.find('.shop-filter-price-start').text(_filterOptions.filter_commonPriceMin);
                    _self.$el.find('.shop-filter-price-end').text(_filterOptions.filter_commonPriceMax);

                    _self.trigger('mview:filter');
                });

                // product origins
                _self.$el.find('input[name="shopFilterBrand"]').on('change', function () {
                    if ($(this).is(':checked'))
                        _filterOptions.filter_categoryBrands.push($(this).val());
                    else
                        _filterOptions.filter_categoryBrands = _.without(_filterOptions.filter_categoryBrands, $(this).val());

                    $.cookie('filter_categoryBrands', _filterOptions.filter_categoryBrands, {path: Backbone.history.fragment});

                    _self.trigger('mview:filter');
                });

                // filter button
                _self.$el.find('.shop-apply-filter').on('click', function () {
                    app.log(_filterOptions);
                    _self.trigger('mview:filter');
                });

                _uiElementsAreEnhanced = true;

                _filterIsBusy = false;
            });
        },

        applyFiltering: function (filterOptions) {

            // prepare filter params
            // filterOptions

            // and then update url data to
            // get new list of products rendered
            // this.model.setUrlData();
            var _filter = JSON.parse(JSON.stringify(this.model.getUrlData()));

            app.log(true, 'view shopProductListCatalog: oring data', _filter);
            app.log(true, 'view shopProductListCatalog: filterOptions', filterOptions);

            _(filterOptions).each(function(value, key){
                if (!_filter[key])
                    return _filter[key] = value;

                if (_.isArray(value))
                    return _filter[key] = value;

                if (_.isString(value))
                    return _filter[key] = value;

                if (_.isNumber(value))
                    return _filter[key] = value;

                if (_.isBoolean(value))
                    return _filter[key] = value;

                if (_.isObject(value) && !_.isObject(_filter[key]))
                    return _filter[key] = value;

                if (_.isObject(value) && _.isObject(_filter[key]))
                    return _filter[key] = _.extend({}, _filter[key], value);

            });

            app.log(true, 'view shopProductListCatalog: applying new filterOptions', _filter);

            this.model.setUrlData(_filter);

        }

    });

    return ProductListCatalog;

});