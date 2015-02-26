define([
    'sandbox',
    'underscore',
    'backbone',
    'utils',
    'cache',
    'plugins/shop/toolbox/js/view/settingsAlerts',
    'plugins/shop/toolbox/js/view/settingsDeliveryAgencies',
    'plugins/shop/toolbox/js/view/settingsWebsiteFormOrder',
    'plugins/shop/toolbox/js/view/settingsAddress',
    'plugins/shop/toolbox/js/view/settinsProductView',
    'plugins/shop/toolbox/js/view/settingsSEO',
    'plugins/shop/toolbox/js/view/settingsExchangeRates',
    'plugins/shop/toolbox/js/view/settingsExchangeRatesDisplay',
    'plugins/shop/toolbox/js/view/widgetPrivatBankExchangerates',
    /* template */
    'hbs!plugins/shop/toolbox/hbs/settings',
    /* lang */
    'i18n!plugins/shop/toolbox/nls/translation',
    'base/js/components/bootstrap3-editable',
    'base/js/lib/jquery.maskedinput',
    'base/js/lib/bootstrap-switch'
], function (Sandbox, _, Backbone, Utils, Cache, ViewSettingsAlerts,
    ViewSettingsDeiveryAgencies, ViewSettingsWebsiteFormOrder, ViewSettingsAddress,
    ViewSettingsProduct, ViewSettingsSEO, ViewExchangeRates, ViewExchangeRatesDisplay, ViewWidgetPrivatBankExchageRates, tpl, lang) {

    var Settings = Backbone.View.extend({
        template: tpl,
        lang: lang,
        className: 'shop-settings',
        initialize: function (options) {
            // set options
            // ini sub-views
            // debugger;
            this.viewAlerts = new ViewSettingsAlerts();
            this.viewDeliveriesList = new ViewSettingsDeiveryAgencies();
            this.viewWebsiteFormOrder = new ViewSettingsWebsiteFormOrder();
            this.viewAddress = new ViewSettingsAddress();
            this.viewProduct = new ViewSettingsProduct();
            this.viewSEO = new ViewSettingsSEO();
            this.viewExchangeRates = new ViewExchangeRates();
            this.viewExchangeRatesDisplay = new ViewExchangeRatesDisplay();
            this.viewWidgetPrivatBankExchageRates = new ViewWidgetPrivatBankExchageRates();

            // // subscribe on events
            // this.listenTo(this.viewProductsList.collection, 'reset', this.render);
            this.render();
        },
        render: function () {
            // TODO:
            // add expired and todays products
            // permanent layout and some elements
            if (this.$el.is(':empty')) {
                this.$el.html(tpl(Utils.getHBSTemplateData(this)));
                this.$('.shop-settings-alerts').html(this.viewAlerts.$el);
                this.$('.delivery-agencies').html(this.viewDeliveriesList.$el);
                this.$('.website-form-order').html(this.viewWebsiteFormOrder.$el);
                this.$('.shop-address').html(this.viewAddress.$el);
                this.$('.shop-product').html(this.viewProduct.$el);
                this.$('.shop-seo').html(this.viewSEO.$el);
                this.$('.shop-exchange-rates').html(this.viewExchangeRates.$el);
                this.$('.shop-exchange-rates-display').html(this.viewExchangeRatesDisplay.$el);
                this.$('.shop-widget-privatbank-exchnage-rates').html(this.viewWidgetPrivatBankExchageRates.$el);
                // this.$('.shop-settings .switcher').bootstrapSwitch({
                //     size: 'mini',
                //     wrapperClass: 'delivery'
                // });
                // this.$('.shop-address .editable').editable({
                //     mode: 'inline'
                // });
                // this.$('.shop-address .myeditable_phone').on('shown', function () {
                //     // debugger;
                //     $(this).data('editable').input.$input.mask('(999) 999-99-99');
                // });
            }
            return this;
        }
    });

    return Settings;
});