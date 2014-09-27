define("plugin/shop/toolbox/js/view/dashboard", [
    'default/js/lib/backbone',
    'default/js/lib/utils',
    'plugin/shop/toolbox/js/view/statsOrdersPending',
    'plugin/shop/toolbox/js/view/statsOrdersExpired',
    'plugin/shop/toolbox/js/view/statsOrdersOverview',
    'plugin/shop/toolbox/js/view/statsProductsOverview',
    'plugin/shop/toolbox/js/view/statsOrdersIntensityLastMonth',
    'plugin/shop/toolbox/js/view/statsProductsIntensityLastMonth',
    'plugin/shop/toolbox/js/view/statsProductsPopular',
    'plugin/shop/toolbox/js/view/statsProductsNonPopular',
    /* template */
    'default/js/plugin/hbs!plugin/shop/toolbox/hbs/dashboard',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/toolbox/nls/translation'
], function (Backbone, Utils, ViewStatsOrdersPending, ViewStatsOrdersExpired, 
    ViewStatsOrdersOverview, ViewStatsProductsOverview, ViewStatsOrdersIntensityLastMonth,
    ViewStatsProductsIntensityLastMonth, ViewStatsProductsPopular, ViewStatsProductsNonPopular, tpl, lang) {

    return Backbone.View.extend({
        id: 'shop-stats-ID',
        className: 'plugin-shop-stats',
        lang: lang,
        template: tpl,
        render: function () {
            var wgtOrdersPending = new ViewStatsOrdersPending();
            var wgtOrdersExpired = new ViewStatsOrdersExpired();
            var wgtOrdersOverview = new ViewStatsOrdersOverview();
            var wgtProductsOverview = new ViewStatsProductsOverview();
            var wgtOrdersIntensityLastMonth = new ViewStatsOrdersIntensityLastMonth();
            var wgtProductsIntensityLastMonth = new ViewStatsProductsIntensityLastMonth();
            var wgtProductsPopular = new ViewStatsProductsPopular();
            var wgtProductsNonPopular = new ViewStatsProductsNonPopular();

            wgtOrdersPending.collection.fetch({reset: true});
            wgtOrdersExpired.collection.fetch({reset: true});
            wgtOrdersOverview.model.fetch();
            wgtProductsOverview.model.fetch();
            wgtOrdersIntensityLastMonth.model.fetch();
            wgtProductsIntensityLastMonth.model.fetch();
            wgtProductsPopular.collection.fetch({reset: true});
            wgtProductsNonPopular.collection.fetch({reset: true});

            this.$el.html(tpl(Utils.getHBSTemplateData(this)));

            this.$('.ordersPending').html(wgtOrdersPending.render().$el);
            this.$('.ordersExpired').html(wgtOrdersExpired.render().$el);
            this.$('.ordersOverview').html(wgtOrdersOverview.$el);
            this.$('.productsOverview').html(wgtProductsOverview.$el);
            this.$('.ordersIntensityLastMonth').html(wgtOrdersIntensityLastMonth.$el);
            this.$('.productsIntensityLastMonth').html(wgtProductsIntensityLastMonth.$el);
            this.$('.productsPopular').html(wgtProductsPopular.render().$el);
            this.$('.productsNonPopular').html(wgtProductsNonPopular.render().$el);
            
            // self.$('select').select2();
            // this.$('.helper').tooltip();
            return this;
        }
    });
});