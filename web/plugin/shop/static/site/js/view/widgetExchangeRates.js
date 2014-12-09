define("plugin/shop/site/js/view/widgetExchangeRates", [
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'default/js/lib/utils',
    'default/js/lib/cache',
    /* template */
    'default/js/plugin/hbs!plugin/shop/site/hbs/widgetExchangeRates',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/site/nls/translation'
], function (_, Backbone, Utils, Cache, tpl, lang) {

    var WidgetExchangeRates = Backbone.View.extend({
        className: 'btn-group exchange-rates-widget',
        id: 'exchange-rates-widget-ID',
        template: tpl,
        lang: lang,
        events: {
            'click .currency': 'changeCurrency'
        },
        render: function () {
            var data = Utils.getHBSTemplateData(this);
            this.$el.html(this.template(data));
            var currencyName = WidgetExchangeRates.getActiveCurrencyName();
            if (currencyName) {
                this.$('.active-currency').text(this.$('.dropdown-menu .' + currencyName).text());
            }
            return this;
        },
        changeCurrency: function (event) {
            var currencyName = $(event.target).parents('li').data('currency');
            Cache.set('userСurrencyName', currencyName);
            this.$('.active-currency').text(this.$('.dropdown-menu .' + currencyName).text());
            Backbone.trigger('changed:plugin-shop-currency', currencyName);
        }
    }, {
        getActiveCurrencyName: function () {
            return Cache.get('userСurrencyName') || APP.instances.shop.settings.DBPriceCurrencyType.Value;
        }
    });

    return WidgetExchangeRates;

});