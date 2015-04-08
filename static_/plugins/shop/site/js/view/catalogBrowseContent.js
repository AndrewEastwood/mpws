define([
    'underscore',
    'backbone',
    'handlebars',
    'utils',
    'plugins/shop/site/js/collection/listProductCatalog',
    'plugins/shop/site/js/view/productItem',
    'text!plugins/shop/site/hbs/catalogBrowseContent.hbs',
    /* lang */
    'i18n!plugins/shop/site/nls/translation',
    'bootstrap'
], function (_, Backbone, Handlebars, Utils, CollListProductCatalog, ProductItem, tpl, lang) {

    var CatalogBrowse = Backbone.View.extend({
        className: 'shop-catalog-browse',
        template: Handlebars.compile(tpl), // check
        lang: lang,
        getPathInCatalog: function () {
            return this.collection._location;
        },
        getDisplayName: function () {
            return this.collection.category.Name;
        },
        getCatalogUrl: function () {
            var externalKey = this.collection.category.ExternalKey,
                pageNo = this.collection.getFilter('filter_viewPageNum');
            return CatalogBrowse.plugin.getCatalogUrl(externalKey, pageNo);
        },
        initialize: function (options) {
            this.options = options || {};
            // set default style
            this.options.design = _.extend({style: 'short'}, this.options.design || {});

            this.collection = CollListProductCatalog.getInstance();
            this.collection.on('sync', this.render, this);
            this.collection.on('reset', this.render, this);
        },
        render: function () {
            var that = this,
                data = Utils.getHBSTemplateData(this);
            data.filter = this.collection.filter;
            data.pagination = this.collection.pagintaion;

            this.$el.html(this.template(data));
            this.collection.each(function(model){
                var productView = new ProductItem(_.extend({}, that.options, {model: model}));
                // productView.render().$el.attr('class', 'shop-product-item shop-product-item-short col-xs-12 col-sm-6 col-md-4 col-lg-4');
                that.$('.displayItems').append(productView.render().$el);
            });
            this.delegateEvents();
            this.trigger('render:complete');
            return this;
        },
        getPageAttributes: function () {
            // seo start
            var formatTitle = "",
                formatKeywords = "",
                formatDescription = "";
            if (APP.instances.shop.settings.SEO.CategoryPageTitle) {
                formatTitle = APP.instances.shop.settings.SEO.CategoryPageTitle;
            }
            if (APP.instances.shop.settings.SEO.CategoryKeywords) {
                formatKeywords = APP.instances.shop.settings.SEO.CategoryKeywords;
            }
            if (APP.instances.shop.settings.SEO.CategoryDescription) {
                formatDescription = APP.instances.shop.settings.SEO.CategoryDescription;
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

            return {
                title: title,
                keywords: keywords,
                description: description,
                type: 'catalog',
                image: null,
                url: Handlebars.helpers.bb_link(APP.instances.shop.urls.shopCatalogCategory, {asRoot: true})
            };
            // seo end
        }
    });

    return CatalogBrowse;

});