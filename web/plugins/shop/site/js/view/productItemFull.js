define([
    'backbone',
    'underscore',
    'plugins/shop/site/js/view/productItemShort',
    'plugins/shop/site/js/model/product',
    'utils',
    'bootstrap-dialog',
    'hbs!plugins/shop/site/hbs/productItemFull',
    /* lang */
    'i18n!plugins/shop/site/nls/translation',
    /* enhanced ui */
    'bootstrap-magnify',
    'lightbox',
    'base/js/lib/jquery.sparkline'
], function (Backbone, _, ViewProductItemShort, ModelProduct, Utils, BootstrapDialog, tpl, lang) {

    var ProductItemFull = ViewProductItemShort.extend({
        className: 'shop-product-item shop-product-item-full',
        template: tpl,
        lang: lang,
        events: {
            'click .open-popup-shipping': 'openPopupShipping',
            'click .open-popup-payments': 'openPopupPayments',
            'click .open-popup-openhours': 'openPopupOpenHours',
            'click .open-popup-phones': 'openPopupPhones',
            'click .open-popup-warranty': 'openPopupWarranty'
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

            // seo start
            var formatTitle = "",
                formatKeywords = "",
                formatDescription = "";
            if (APP.instances.shop.settings.SEO.ProductPageTitle) {
                formatTitle = APP.instances.shop.settings.SEO.ProductPageTitle;
            }
            if (APP.instances.shop.settings.SEO.ProductKeywords) {
                formatKeywords = APP.instances.shop.settings.SEO.ProductKeywords;
            }
            if (APP.instances.shop.settings.SEO.ProductDescription) {
                formatDescription = APP.instances.shop.settings.SEO.ProductDescription;
            }

            var searchValues = ['\\[ProductName\\]', '\\[CategoryName\\]', '\\[OriginName\\]', '\\[ProductModel\\]', '\\[ProductDisplayTitle\\]'];
            var replaceValues = [data.Name, data._category.Name, data._origin.Name, data.Model, data._displayName];

            var title = APP.utils.replaceArray(formatTitle, searchValues, replaceValues);
            var keywords = APP.utils.replaceArray(formatKeywords, searchValues, replaceValues);
            var description = APP.utils.replaceArray(formatDescription, searchValues, replaceValues);

            APP.Sandbox.eventNotify('global:page:setTitle', title);
            APP.Sandbox.eventNotify('global:page:setKeywords', keywords);
            APP.Sandbox.eventNotify('global:page:setDescription', description);
            // seo end

            return this;
        },
        openPopupShipping: function (event) {
            BootstrapDialog.show({
                draggable: false,
                cssClass: 'popup-shop-info popup-shop-shipping',
                type: BootstrapDialog.TYPE_WARNING,
                title: $(event.target).html().trim(),
                message: APP.instances.shop.settings._activeAddress.InfoShipping
            });
        },
        openPopupPayments: function (event) {
            BootstrapDialog.show({
                draggable: false,
                cssClass: 'popup-shop-info popup-shop-payments',
                type: BootstrapDialog.TYPE_WARNING,
                title: $(event.target).html().trim(),
                message: APP.instances.shop.settings._activeAddress.InfoPayment
            });
        },
        openPopupOpenHours: function (event) {
            BootstrapDialog.show({
                draggable: false,
                cssClass: 'popup-shop-info popup-shop-openhours',
                type: BootstrapDialog.TYPE_WARNING,
                title: $(event.target).html().trim(),
                message: function () {
                    var $openHoursList = $('<ul>').addClass('list-group');
                    _(APP.instances.shop.settings._activeAddress.OpenHoursMap).each(function (item) {
                        $openHoursList.append($('<li>').addClass('list-group-item ' + (item.isToday ? 'active' : '')).append([
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
                draggable: false,
                cssClass: 'popup-shop-info popup-shop-phones',
                type: BootstrapDialog.TYPE_WARNING,
                title: $(event.target).html().trim(),
                message: function () {
                    var $contactsList = $('<ul>').addClass('list-group');
                    _(APP.instances.shop.settings._activeAddress.PhonesMap).each(function (item) {
                        $contactsList.append($('<li>').addClass('list-group-item').append([
                            $('<span>').addClass('badge').text(item.value),
                            item.label
                        ]));
                    });
                    return $contactsList;
                }
            });
        },
        openPopupWarranty: function (event) {
            BootstrapDialog.show({
                draggable: false,
                cssClass: 'popup-shop-info popup-shop-warranty',
                type: BootstrapDialog.TYPE_WARNING,
                title: $(event.target).html().trim(),
                message: APP.instances.shop.settings._activeAddress.InfoWarranty
            });
        }
    });

    return ProductItemFull;
});