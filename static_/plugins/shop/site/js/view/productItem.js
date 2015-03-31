define([
    'underscore',
    'backbone',
    'handlebars',
    'utils',
    'bootstrap-dialog',
    'plugins/shop/site/js/model/product',
    'text!plugins/shop/site/hbs/productItemMinimal.hbs',
    'text!plugins/shop/site/hbs/productItemShort.hbs',
    'text!plugins/shop/site/hbs/productItemFull.hbs',
    /* lang */
    'i18n!plugins/shop/site/nls/translation',
    /* enhanced ui */
    'bootstrap-magnify',
    'lightbox',
    'base/js/lib/jquery.sparkline'
], function (_, Backbone, Handlebars, Utils, BootstrapDialog, ModelProduct, tplMinimal, tplShort, tplFull, lang) {

    // Handlebars.registerDynamicHelper('shopProductTitle', function (data, opts) {
    //     return opts.fn(data._origin.Name + ' ' + data.Model);
    // });
    var ProductItemShort = Backbone.View.extend({
        className: 'shop-product-item',// 'shop-product-item shop-product-item-short col-xs-12 col-sm-6 col-md-3 col-lg-3',
        templates: {
            short: Handlebars.compile(tplShort), // check
            minimal: Handlebars.compile(tplMinimal), // check
            full: Handlebars.compile(tplFull), // check
        },
        lang: lang,
        events: {
            'click .add-to-cart': 'noop',
            'click .add-to-wishlist': 'noop',
            'click .add-to-compare': 'noop',
            'click .open-popup-shipping': 'openPopupShipping',
            'click .open-popup-payments': 'openPopupPayments',
            'click .open-popup-openhours': 'openPopupOpenHours',
            'click .open-popup-phones': 'openPopupPhones',
            'click .open-popup-warranty': 'openPopupWarranty',
            'click .le-quantity a': 'updateQuantity'
        },
        noop: $.noop,
        initialize: function (options) {
            this.options = options || {};
            // set default style
            this.options.design = _.extend({style: 'short'}, this.options.design || {});
            // bind context
            _.bindAll(this, 'refresh', 'switchCurrency', 'updateQuantity');
            // APP.Sandbox.eventSubscribe('plugin:shop:list_wish:changed', this.refresh);
            // APP.Sandbox.eventSubscribe('plugin:shop:list_compare:changed', this.refresh);
            // APP.Sandbox.eventSubscribe('plugin:shop:order:changed', this.refresh);

            // refresh price when currency widget is changed
            Backbone.on('changed:plugin-shop-currency', this.switchCurrency);
            if (this.options.productID) {
                this.model = new ModelProduct({
                    ID: this.options.productID
                });
            }
            if (this.model) {
                this.listenTo(this.model, 'change', this.render);
                this.listenTo(this.model, 'sync', this.render);
            }
        },
        refresh: function (data) {
            if (this.model) {
                if (data && data.id && (data.id === this.model.id || data.id === "*")) {
                    this.model.fetch();
                }
            }
        },
        render: function () {
            var design = this.options.design,
                tpl = this.templates[design.style];
            this.$el.addClass('shop-product-item-' + design.style);
            this.$el.html(tpl(Utils.getHBSTemplateData(this)));
            // shop pulse animation for cart button badge
            if (this.model.hasChanged('_viewExtras') && this.model.previous('_viewExtras') && this.model.get('_viewExtras').InCartCount !== this.model.previous('_viewExtras').InCartCount)
                this.$('.btn.withNotificationBadge .badge').addClass("pulse").delay(1000).queue(function(){
                    $(this).removeClass("pulse").dequeue();
                });
            this.$('[data-toggle="tooltip"]').tooltip();
            this.$('.product-availability .fa').popover({trigger: 'hover'});

            if (design.className) {
                this.$el.addClass(design.className);
            }
            if (this.isStyleFull()) {
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
            }

            if (design.wrap) {
                this.$el = $(design.wrap).html(this.$el);
            }

            this.trigger('render:complete');
            return this;
        },
        getPathInCatalog: function () {
            var _category = this.model.get('_category'),
                pathItems = [];
            if (!_category || !_category._location) {
                return pathItems;
            }
            _(_category._location).each(function (locItem) {
                pathItems.push(_.extend({}, locItem, {
                    url: Handlebars.helpers.bb_link(APP.instances.shop.urls.shopCatalogCategory, {
                        asRoot: true,
                        category: locItem.ExternalKey
                    })
                }));
            });
            return pathItems;
        },
        getDisplayName: function () {
            return this.model && this.model.get('_displayName');
        },
        getProductUrl: function () {
            return this.model && Handlebars.helpers.bb_link(APP.instances.shop.urls.shopProduct, {asRoot: true, product: this.model.get('ExternalKey')});
        },
        getSelectedQuantity: function () {
            var $qInput = this.$('input[name="quantity"]');
            return parseInt($qInput.val(), 10);
        },
        updateQuantity: function (e) {
            // Quantity Spinner
            e.preventDefault();
            var $targetBtn = $(e.target),
                $qInput = this.$('input[name="quantity"]'),
                currentQty = parseInt($qInput.val(), 10);
            if ($targetBtn.hasClass('minus') && currentQty > 1) {
                $qInput.val(currentQty - 1);
            } else if($targetBtn.hasClass('plus') && currentQty < 99) {
                $qInput.val(currentQty + 1);
            }
        },
        isStyleFull: function () {
            return this.options.design.style === 'full';
        },
        getPageAttributes: function () {
            var data = this.model.toJSON();
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

            return {title: title, keywords: keywords, description: description};
            // seo end
        },
        switchCurrency: function (visibleCurrencyName) {
            this.$('.moneyValue').addClass('hidden');
            this.$('.moneyValue.' + visibleCurrencyName).removeClass('hidden');
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

    return ProductItemShort;

});