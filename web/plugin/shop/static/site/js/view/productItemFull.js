define("plugin/shop/site/js/view/productItemFull", [
    'default/js/lib/sandbox',
    'default/js/lib/backbone',
    'default/js/lib/underscore',
    'plugin/shop/site/js/view/productItemShort',
    'plugin/shop/site/js/model/product',
    'default/js/lib/utils',
    'default/js/lib/bootstrap-dialog',
    'default/js/plugin/hbs!plugin/shop/site/hbs/productItemFull',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/site/nls/translation',
    /* enhanced ui */
    'default/js/lib/bootstrap-magnify',
    'default/js/lib/lightbox',
    'default/js/lib/jquery.sparkline'
], function (Sandbox, Backbone, _, ViewProductItemShort, ModelProduct, Utils, BootstrapDialog, tpl, lang) {

    var ProductItemFull = ViewProductItemShort.extend({
        className: 'shop-product-item shop-product-item-full',
        template: tpl,
        lang: lang,
        events: {
            'click .open-popup-shipping': 'openPopupShipping',
            'click .open-popup-payments': 'openPopupPayments',
            'click .open-popup-openhours': 'openPopupOpenHours',
            'click .open-popup-phones': 'openPopupPhones',
        },
        initialize: function (options) {
            this.model = new ModelProduct({
                ID: options.productID
            });
            ViewProductItemShort.prototype.initialize.call(this);
            // this.listenTo(this.model, 'change', this.render);
        },
        render: function () {
            ViewProductItemShort.prototype.render.call(this);
            this.$('.shop-product-image-main img').magnify();
            // show price chart (powered by http://omnipotent.net/jquery.sparkline)
            var _prices = _(this.model.get('Prices') || {}).values();
            if (_prices.length) {
                this.$("#sparkline").sparkline(_prices, {
                    type: 'bar',
                    width: '150px',
                    height: '15px',
                    lineColor: '#cf7400',
                    fillColor: false,
                    drawNormalOnTop: true
                });
            }

            var data = this.model.toJSON();
            APP.getCustomer().setBreadcrumb({
                categories: data._category && data._category._location,
                product: data
            });

            return this;
        },
        openPopupShipping: function (event) {
            BootstrapDialog.show({
                type: BootstrapDialog.TYPE_WARNING,
                title: $(event.target).html().trim(),
                message: APP.instances.shop.settings._activeAddress.Shipping.Value
            });
        },
        openPopupPayments: function (event) {
            BootstrapDialog.show({
                type: BootstrapDialog.TYPE_WARNING,
                title: $(event.target).html().trim(),
                message: APP.instances.shop.settings._activeAddress.Payment.Value
            });
        },
        openPopupOpenHours: function (event) {
            BootstrapDialog.show({
                type: BootstrapDialog.TYPE_WARNING,
                title: $(event.target).html().trim(),
                message: function () {
                    var $openHoursList = $('<ul>').addClass('list-group'),
                        today = APP.instances.shop.settings._activeAddress.OpenHoursToday;
                    _(APP.instances.shop.settings._activeAddress.OpenHoursDaysMap).each(function (item) {
                        $openHoursList.append($('<li>').addClass('list-group-item ' + (today.day === item.day ? 'active' : '')).append([
                            $('<span>').addClass('badge').text(item.hours),
                            item.day
                        ]));
                    });
                    return $openHoursList;
                }
            });
        },
        openPopupPhones: function (event) {
            BootstrapDialog.show({
                type: BootstrapDialog.TYPE_WARNING,
                title: $(event.target).html().trim(),
                message: function () {
                    
                }
            });
        }
    });

    return ProductItemFull;
});