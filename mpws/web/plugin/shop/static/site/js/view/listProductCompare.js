define("plugin/shop/site/js/view/listProductCompare", [
    'default/js/lib/backbone',
    'plugin/shop/site/js/collection/listProductCompare',
    'default/js/lib/utils',
    'default/js/plugin/hbs!plugin/shop/site/hbs/listProductCompare',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/site/nls/translation',
    "default/js/lib/jquery.cookie"
], function (Backbone, compareCollectionInstance, Utils, tpl, lang) {

    var ListProductCompare = Backbone.View.extend({
        collection: compareCollectionInstance,
        className: 'row shop-products-compare clearfix',
        id: 'shop-products-compare-ID',
        template: tpl,
        lang: lang,
        initialize: function () {
            this.listenTo(this.collection, 'reset', this.render);
            this.listenTo(this.collection, 'sync', this.render);
        },
        render: function() {
            var self = this;
            var tplData = Utils.getHBSTemplateData(this);

            // get all product features
            var _productFeatures= [];
            this.collection.each(function(model){
                // debugger;
                _productFeatures.push(model.getFeatures(self.collection));
            });

            // transform collection to object with array values
            var productFeatuesTable = {};
            debugger;
            _(_productFeatures).each(function(v, k){
                productFeatuesTable[k] = _(_productFeatures).pluck(k);
            });
            // debugger;

            tplData.productFeatues = productFeatuesTable;
            this.$el.html(this.template(tplData));
            return this;
        }
    });

    return ListProductCompare;

});