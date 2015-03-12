define([
    'underscore',
    'handlebars',
    'backbone',
    'utils',
    'cachejs',
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
    'text!plugins/shop/toolbox/hbs/settings.hbs',
    /* lang */
    'i18n!plugins/shop/toolbox/nls/translation',
    'bootstrap-editable',
    'jquery.maskedinput',
    'bootstrap-switch'
], function (_, Backbone, Handlebars, Utils, Cache, ViewSettingsAlerts,
    ViewSettingsDeiveryAgencies, ViewSettingsWebsiteFormOrder, ViewSettingsAddress,
    ViewSettingsProduct, ViewSettingsSEO, ViewExchangeRates, ViewExchangeRatesDisplay, ViewWidgetPrivatBankExchageRates, tpl, lang) {

    var Settings = Backbone.View.extend({
        template: Handlebars.compile(tpl), // check
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
                this.$el.html(this.template(Utils.getHBSTemplateData(this)));
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