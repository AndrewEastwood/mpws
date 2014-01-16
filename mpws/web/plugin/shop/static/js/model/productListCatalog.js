define("plugin/shop/js/model/productListCatalog", [
    'default/js/lib/underscore',
    'default/js/model/mmodel',
    'plugin/shop/js/lib/utils',
    /* js extensions */
    'default/js/lib/jquery.cookie',
], function (_, MModel, shopUtils) {

    var ProductListCatalog = MModel.extend({

        _options: {
            realm: 'plugin',

            caller: 'shop',

            fn: 'shop_catalog',

            urldata: {

                categoryId: null,

                // view options

                filter_viewSortBy: $.cookie('filter_viewSortBy') || null,

                filter_viewItemsOnPage: $.cookie('filter_viewItemsOnPage') || null,

                // common
                // these options are common for all existed categories
                // so we just keep them here and render them at very top
                // of the filter panel

                filter_commonPriceMax: $.cookie('filter_commonPriceMax') || null,

                filter_commonPriceMin: $.cookie('filter_commonPriceMin') || null,

                filter_commonAvailability: $.cookie('filter_commonAvailability') ? $.cookie('filter_commonAvailability').split(',') : [],

                filter_commonOnSaleTypes: $.cookie('filter_commonOnSaleTypes') ? $.cookie('filter_commonOnSaleTypes').split(',') : [],

                // category based (use specifications of current category)
                // these options have category specific options and they are
                // being rendered under the common options

                filter_categoryBrands: $.cookie('filter_categoryBrands') ? $.cookie('filter_categoryBrands').split(',') : [],

                // filter_categorySubCategories: [],

                filter_categorySpecifications: []

            }
        },

        initialize: function (options) {

            // TODO: update url data with cookies

            // app.log('model ProductListCatalog initialize', this, options);
            MModel.prototype.initialize.call(this, _.extend({}, this._options, options));

        },

        parse: function (data) {

            var products = shopUtils.adjustProductEntry(data);
            // app.log('model ProductListCatalog parse', data);

            data.products = products;
            data.attributes;

            return data;
        }

    });

    return ProductListCatalog;

});