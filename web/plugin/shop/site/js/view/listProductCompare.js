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
        events: {
            'click .compare-mode': 'toggleCompareMode'
        },
        initialize: function () {
            this.listenTo(this.collection, 'reset', this.render);
            this.listenTo(this.collection, 'sync', this.render);
            Backbone.on('changed:plugin-shop-currency', this.switchCurrency);
            _.bindAll(this, 'toggleCompareMode');
        },
        render: function() {
            var self = this;
            var tplData = Utils.getHBSTemplateData(this);
            var features = {};
            var productIDs = [];
            // merge all features
            this.collection.each(function(model){
                // debugger;
                var f = model.get('Features');
                _(f).each(function (groupFeatures, groupName) {
                    features[groupName] = features[groupName] || {};
                    _(groupFeatures).each(function (featureName) {
                        features[groupName][featureName] = features[groupName][featureName] || [];
                        features[groupName][featureName].push(model.id);
                    });
                });
                productIDs.push(model.id);
            });
            tplData.productFeatues = features;
            tplData.productIDs = productIDs;
            tplData.showCompareModeLink = this.collection.length > 1;
            this.$el.html(this.template(tplData));

            return this;
        },
        toggleCompareMode: function (event) {
            var that = this,
                $el = $(event.target).parents('.compare-mode');
            $el.toggleClass('all');
            if (!$el.hasClass('all')) {
                this.$('.can-toggle').each(function () {
                    var values = $(this).find('.product-param').map(function () { return $(this).text(); }).get();
                    var hasDiff = that.collection.length > 1 && _(values).uniq().length > 1;
                    $(this).toggleClass('hidden', !hasDiff);
                });
                this.$('.feature-group').each(function () {
                    var groupName = $(this).data('name');
                    var all = that.$('.feature-item.owner-' + groupName).length;
                    var hidden = that.$('.feature-item.hidden.owner-' + groupName).length;
                    $(this).toggleClass('hidden', all === hidden);
                });
            } else {
                this.$('.can-toggle, .feature-group').removeClass('hidden');
            }
        },
        switchCurrency: function (visibleCurrencyName) {
            this.$('.moneyValue').addClass('hidden');
            this.$('.moneyValue.' + visibleCurrencyName).removeClass('hidden');
        }
    });

    return ListProductCompare;

});