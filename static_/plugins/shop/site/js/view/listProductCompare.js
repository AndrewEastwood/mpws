define([
    'backbone',
    'handlebars',
    'plugins/shop/site/js/collection/listProductCompare',
    'utils',
    'text!plugins/shop/site/hbs/listProductCompare.hbs',
    /* lang */
    'i18n!plugins/shop/site/nls/translation',
    'jquery.cookie'
], function (Backbone, Handlebars, CollCompareList, Utils, tpl, lang) {

    var ListProductCompare = Backbone.View.extend({
        collection: CollCompareList.getInstance(),
        className: 'shop-productlist-compare',
        template: Handlebars.compile(tpl), // check
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
            this.trigger('shop:rendered');
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
            this.$('.shop-price-value').addClass('hidden');
            this.$('.shop-price-value.' + visibleCurrencyName).removeClass('hidden');
        }
    });

    return ListProductCompare;

});