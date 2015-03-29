define([
    'backbone',
    'plugins/shop/site/js/collection/listProducts',
    'plugins/shop/site/js/view/productItem'
], function (Backbone, CollListProducts, ProductItem, tplProductItemMinimal) {

    // debugger;
    var ListProductLatest = Backbone.View.extend({
        className: 'shop-product-list clearfix',
        initialize: function (options) {
            this.options = options || {};
            this.collection = new CollListProducts(this.options);
            this.collection.on('reset', this.render, this);
        },
        render: function () {
            var that = this,
                isList = this.options.design && this.options.design.asList || false,
                $list = $('<ul/>');
            if (this.options.type) {
                this.$el.addClass('shop-product-list-' + this.options.type);
            }
            if (isList) {
                this.tagName = 'ul';
            }
            this.collection.each(function (model) {
                var productView = new ProductItem(_.extend({}, that.options, {model: model})),
                    $productEl = productView.render().$el;
                if (isList) {
                    $list.append($('<li/>').html($productEl));
                } else {
                    that.$el.append($productEl);
                }
            });
            if (isList) {
                this.$el.html($list);
            }
            this.trigger('shop:rendered');
            return this;
        },
        getPageAttributes: function () {
            // seo start
            var formatTitle = "",
                formatKeywords = "",
                formatDescription = "";
            if (APP.instances.shop.settings.SEO.HomePageTitle) {
                formatTitle = APP.instances.shop.settings.SEO.HomePageTitle;
            }
            if (APP.instances.shop.settings.SEO.HomePageKeywords) {
                formatKeywords = APP.instances.shop.settings.SEO.HomePageKeywords;
            }
            if (APP.instances.shop.settings.SEO.HomePageDescription) {
                formatDescription = APP.instances.shop.settings.SEO.HomePageDescription;
            }

            var catalogFirst10ProductNames = [], catalogFirst10OriginNames = [], catalogFirst10ProductModels = [], catalogFirst10CategoryNames = [];
            if (this.collection.length) {
                for (var i = 0, len = this.collection.length > 10 ? 10 : this.collection.length; i < len; i++) {
                    var productModel = this.collection.at(i);
                    catalogFirst10ProductNames.push(productModel.get('Name'));
                    catalogFirst10OriginNames.push(productModel.get('_origin')['Name']);
                    catalogFirst10CategoryNames.push(productModel.get('_category')['Name']);
                    catalogFirst10ProductModels.push(productModel.get('Model'));
                }
            }

            // make them uniq
            catalogFirst10ProductNames = _(catalogFirst10ProductNames).uniq();
            catalogFirst10OriginNames = _(catalogFirst10OriginNames).uniq();
            catalogFirst10ProductModels = _(catalogFirst10ProductModels).uniq();
            catalogFirst10CategoryNames = _(catalogFirst10CategoryNames).uniq();

            var searchValues = ['\\[CatalogFirst10ProductNames\\]', '\\[CatalogFirst10OriginNames\\]', '\\[CatalogFirst10ProductModels\\]', '\\[CatalogFirst10CategoryNames\\]'];
            var replaceValues = [catalogFirst10ProductNames.join(', '), catalogFirst10OriginNames.join(', '), catalogFirst10ProductModels.join(', '), catalogFirst10CategoryNames.join(', ')];

            var title = APP.utils.replaceArray(formatTitle, searchValues, replaceValues);
            var keywords = APP.utils.replaceArray(formatKeywords, searchValues, replaceValues);
            var description = APP.utils.replaceArray(formatDescription, searchValues, replaceValues);

            return {title: title, keywords: keywords, description: description};
            // seo end
        }
    });

    return ListProductLatest;

});