define('plugin/shop/toolbox/js/view/settings', [
    'default/js/lib/sandbox',
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'default/js/lib/utils',
    'default/js/lib/cache',
    'plugin/shop/toolbox/js/view/settingsAlerts',
    'plugin/shop/toolbox/js/view/settingsDeliveryAgencies',
    'plugin/shop/toolbox/js/view/settingsWebsiteFormOrder',
    'plugin/shop/toolbox/js/view/settingsAddress',
    'plugin/shop/toolbox/js/view/settinsProductView',
    'plugin/shop/toolbox/js/view/settingsSEO',
    'plugin/shop/toolbox/js/view/settingsExchangeRates',
    /* template */
    'default/js/plugin/hbs!plugin/shop/toolbox/hbs/settings',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/toolbox/nls/translation',
    'default/js/components/bootstrap3-editable',
    'default/js/lib/jquery.maskedinput',
    'default/js/lib/bootstrap-switch'
], function (Sandbox, _, Backbone, Utils, Cache, ViewSettingsAlerts,
    ViewSettingsDeiveryAgencies, ViewSettingsWebsiteFormOrder, ViewSettingsAddress,
    ViewSettingsProduct, ViewSettingsSEO, ViewExchangeRates, tpl, lang) {

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