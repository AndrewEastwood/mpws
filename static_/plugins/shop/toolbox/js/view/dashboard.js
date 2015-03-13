define([
    'backbone',
    'handlebars',
    'utils',
    'plugins/shop/toolbox/js/view/statsOrdersPending',
    'plugins/shop/toolbox/js/view/statsOrdersExpired',
    'plugins/shop/toolbox/js/view/statsOrdersOverview',
    'plugins/shop/toolbox/js/view/statsProductsOverview',
    'plugins/shop/toolbox/js/view/statsOrdersIntensityLastMonth',
    'plugins/shop/toolbox/js/view/statsProductsIntensityLastMonth',
    'plugins/shop/toolbox/js/view/statsProductsPopular',
    'plugins/shop/toolbox/js/view/statsProductsNonPopular',
    /* template */
    'text!plugins/shop/toolbox/hbs/dashboard.hbs',
    /* lang */
    'i18n!plugins/shop/toolbox/nls/translation'
], function (Backbone, Handlebars, Utils, ViewStatsOrdersPending, ViewStatsOrdersExpired, 
    ViewStatsOrdersOverview, ViewStatsProductsOverview, ViewStatsOrdersIntensityLastMonth,
    ViewStatsProductsIntensityLastMonth, ViewStatsProductsPopular, ViewStatsProductsNonPopular, tpl, lang) {

    return Backbone.View.extend({
        id: 'shop-stats-ID',
        className: 'plugin-shop-stats',
        lang: lang,
        template: Handlebars.compile(tpl), // check
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

            this.$el.html(this.template(Utils.getHBSTemplateData(this)));

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