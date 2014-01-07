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

            // extend parent
            MView.prototype.initialize.call(this, options);

            var _urlData = this.model.getUrlData();

            _self.on('mview:rendered', function () {
                
                _self.$el.find('#shopProductListFiltering_SortByID').val(_urlData.filter_viewSortBy);
                _self.$el.find('#shopProductListDisplayItems_DisplayCountID').val(_urlData.filter_viewItemsOnPage);

                var _filterDropdowns = _self.$el.find('.selectpicker').selectpicker();

                _filterDropdowns.on('change', function () {
                    // app.log($(this).data('name'),  $(this).val());
                    $.cookie('filter_viewSortBy', _self.$el.find('#shopProductListFiltering_SortByID').val());
                    $.cookie('filter_viewItemsOnPage', _self.$el.find('#shopProductListDisplayItems_DisplayCountID').val());
                })
                
                // price range
                var _filterPrice = _self.$el.find('.slider').slider();
                _filterPrice.on('slideStop', function(){
                    var _priceRange = $(this).data('value');
                    _self.$el.find('.shop-filter-price-start').text(_priceRange[0]);
                    _self.$el.find('.shop-filter-price-end').text(_priceRange[1]);
                });
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