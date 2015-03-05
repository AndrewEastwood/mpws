define([
    'backbone',
    'plugins/shop/site/js/collection/listProductLatest',
    'plugins/shop/site/js/view/productItemShort'
], function (Backbone, CollListProductLatest, ProductItemShort) {

    // debugger;
    var ListProductLatest = Backbone.View.extend({
        className: 'shop-product-list shop-product-list-latest clearfix',
        collection: new CollListProductLatest(),
        initialize: function () {
            // debugger;
            this.collection.on('reset', this.render, this);
        },
        render: function () {
            // debugger;
            var self = this;
            this.collection.each(function(model){
                var productView = new ProductItemShort({model: model});
                self.$el.append(productView.render().el);
            });
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

            APP.Sandbox.eventNotify('global:page:setTitle', title);
            APP.Sandbox.eventNotify('global:page:setKeywords', keywords);
            APP.Sandbox.eventNotify('global:page:setDescription', description);
            // seo end
            return this;
        }
    });

    return ListProductLatest;

});