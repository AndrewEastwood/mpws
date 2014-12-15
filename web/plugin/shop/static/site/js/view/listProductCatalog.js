define("plugin/shop/site/js/view/listProductCatalog", [
    'default/js/lib/sandbox',
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'default/js/lib/utils',
    'plugin/shop/site/js/collection/listProductCatalog',
    'plugin/shop/site/js/view/productItemShort',
    'default/js/lib/bootstrap-dialog',
    'default/js/plugin/hbs!plugin/shop/site/hbs/listProductCatalog',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/site/nls/translation',
    'default/js/lib/bootstrap',
    'default/js/lib/bootstrap-combobox',
    'default/js/lib/bootstrap-slider',
    "default/js/lib/jquery.cookie"
], function (Sandbox, _, Backbone, Utils, CollListProductCatalog, ProductItemShort, dlg, tpl, lang) {

    var ListProductCatalog = Backbone.View.extend({
        className: 'shop-product-list shop-product-list-catalog',
        template: tpl,
        lang: lang,
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
            var that = this;
            // var displayItems = [];
            // var _filterData = ;
            // debugger;
            var data = Utils.getHBSTemplateData(this);
            data.filter = this.collection.filter;
            data.pagination = this.collection.pagintaion;
            this.$el.html(this.template(data));

            this.collection.each(function(model){
                var productView = new ProductItemShort({model: model});
                productView.render();
                // displayItems.push(productView.$el);
                productView.$el.attr('class', 'shop-product-item shop-product-item-short col-xs-12 col-sm-6 col-md-4 col-lg-4');
                that.$('.displayItems').append(productView.$el);
            });

            // // enhance ui components
            // debugger;
            var _filterPrice = this.$('.slider').slider();
            var _filterDropdowns = this.$('.selectpicker').selectpicker();

            APP.getCustomer().setBreadcrumb({
                categories: this.collection._location
            });

            // seo start
            var formatTitle = "",
                formatKeywords = "",
                formatDescription = "";
            if (APP.instances.shop.settings.CategoryPageTitle.Value) {
                formatTitle = APP.instances.shop.settings.CategoryPageTitle.Value;
            }
            if (APP.instances.shop.settings.CategoryKeywords.Value) {
                formatKeywords = APP.instances.shop.settings.CategoryKeywords.Value;
            }
            if (APP.instances.shop.settings.CategoryDescription.Value) {
                formatDescription = APP.instances.shop.settings.CategoryDescription.Value;
            }

            var categoryFirst5Products = [];
            var catalogFirst5ProductNames = [], catalogFirst5OriginNames = [], catalogFirst5ProductModels = [];
            if (this.collection.length) {
                for (var i = 0, len = this.collection.length > 5 ? 5 : this.collection.length; i < len; i++) {
                    var productModel = this.collection.at(i);
                    catalogFirst5ProductNames.push(productModel.get('Name'));
                    catalogFirst5OriginNames.push(productModel.get('_origin')['Name']);
                    catalogFirst5ProductModels.push(productModel.get('Model'));
                }
            }

            // make them uniq
            catalogFirst5ProductNames = _(catalogFirst5ProductNames).uniq();
            catalogFirst5OriginNames = _(catalogFirst5OriginNames).uniq();
            catalogFirst5ProductModels = _(catalogFirst5ProductModels).uniq();

            var searchValues = ['\\[CatalogFirst5ProductNames\\]', '\\[CategoryName\\]', '\\[CatalogFirst5OriginNames\\]', '\\[CatalogFirst5ProductModels\\]'];
            var replaceValues = [catalogFirst5ProductNames.join(', '), this.collection.category.Name, catalogFirst5OriginNames.join(', '), catalogFirst5ProductModels.join(', ')];

            var title = APP.utils.replaceArray(formatTitle, searchValues, replaceValues);
            var keywords = APP.utils.replaceArray(formatKeywords, searchValues, replaceValues);
            var description = APP.utils.replaceArray(formatDescription, searchValues, replaceValues);

            Sandbox.eventNotify('global:page:setTitle', title);
            Sandbox.eventNotify('global:page:setKeywords', keywords);
            Sandbox.eventNotify('global:page:setDescription', description);
            // seo end

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
            var idValuesHolders = ['filter_categorySubCategories', 'filter_commonFeatures', 'filter_categoryBrands'];
            var filterValue = $(event.target).val();
            if (_(idValuesHolders).indexOf(_targetFilterName) >= 0) {
                filterValue = parseInt(filterValue, 10);
            }

            // debugger;
            if ($(event.target).is(':checked'))
                _filterOptions[_targetFilterName].push(filterValue);
            else
                _filterOptions[_targetFilterName] = _.without(_filterOptions[_targetFilterName], filterValue);

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
            var _innerCheckbox = null;
            if ($el.is(':checkbox')) {
                _innerCheckbox = $el;
            } else {
                _innerCheckbox = $el.find('input[type="checkbox"]');
            }
            _innerCheckbox.prop('checked', !_innerCheckbox.prop('checked'));
            _innerCheckbox.trigger('change');
            if (event && event.stopPropagation)
                event.stopPropagation();
            if ($el.parents('a').attr('rel') !== 'category' && $el.attr('rel') !== 'category')
                return false;
        },
        filterProducts_LoadMore: function () {
            // var _filter_viewPageNum = this.collection.getFilter('filter_viewPageNum');
            // _filter_viewPageNum++;
            // this.collection.setFilter('filter_viewPageNum', _filter_viewPageNum);
            // // debugger;
            // this.collection.fetch({update: true, remove: false});
        }
    });

    return ListProductCatalog;

});