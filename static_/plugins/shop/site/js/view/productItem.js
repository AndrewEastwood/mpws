define([
    'jquery',
    'underscore',
    'backbone',
    'handlebars',
    'utils',
    'odometer',
    'bootstrap-dialog',
    'plugins/shop/site/js/model/product',
    'text!plugins/shop/site/hbs/productItemMinimal.hbs',
    'text!plugins/shop/site/hbs/productItemMinimal2.hbs',
    'text!plugins/shop/site/hbs/productItemShort.hbs',
    'text!plugins/shop/site/hbs/productItemFull.hbs',
    'text!plugins/shop/site/hbs/productItemOfferBanner.hbs',
    /* lang */
    'i18n!plugins/shop/site/nls/translation',
    /* enhanced ui */
    'echo',
    'bootstrap-magnify',
    'lightbox',
    'jquery.sparkline'
], function ($, _, Backbone, Handlebars, Utils, Odometer, BootstrapDialog, ModelProduct, tplMinimal, tplMinimal2, tplShort, tplFull, tplOfferBanner, lang, echo) {

    var ProductItem = Backbone.View.extend({
        className: 'shop-product-item',
        templates: {
            short: Handlebars.compile(tplShort), // check
            minimal: Handlebars.compile(tplMinimal), // check
            minimal2: Handlebars.compile(tplMinimal2), // check
            full: Handlebars.compile(tplFull), // check
            offerbanner: Handlebars.compile(tplOfferBanner), // check
        },
        lang: lang,
        events: {
            'click .add-to-cart': 'addToCart',
            'click .add-to-wishlist': 'addToWishList',
            'click .add-to-compare': 'addToCompareList',
            'click .open-popup-shipping': 'openPopupShipping',
            'click .open-popup-payments': 'openPopupPayments',
            'click .open-popup-openhours': 'openPopupOpenHours',
            'click .open-popup-phones': 'openPopupPhones',
            'click .open-popup-warranty': 'openPopupWarranty',
            'click .le-quantity a': 'updateQuantity'
        },
        noop: $.noop,
        initialize: function (options) {
            var that = this;

            this.options = options || {};
            // set default style
            this.options.design = _.extend({style: 'short'}, this.options.design || {});
            this.options.templates = _.extend({}, this.options.templates, this.options.design.templates || {});
            // bind context
            _.bindAll(this, 'switchCurrency', 'updateQuantity', 'addToCart', 'isStyleFull');

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

            ProductItem.plugin.order.on('product:quantity:updated', function (q) {
                that.$el.addClass('shop-product-in-cart');
                that.$('.add-to-cart .in-cart-quantity').html(q);
            });
            ProductItem.plugin.order.on('product:removed', function (q) {
                that.$el.removeClass('shop-product-in-cart');
                that.$('.add-to-cart .in-cart-quantity').html('');
            });
        },
        addToCart: function () {
            ProductItem.plugin.order.setProduct(this.model.id, this.getSelectedQuantity());
        },
        addToWishList: function () {
            ProductItem.plugin.wishList.addProduct(this.model.id, this.getSelectedQuantity());
            this.model.fetch();
        },
        addToCompareList: function () {
            ProductItem.plugin.compareList.addProduct(this.model.id, this.getSelectedQuantity());
            this.model.fetch();
        },
        // refresh: function (data) {
        //     if (this.model) {
        //         if (data && data.id && (data.id === this.model.id || data.id === "*")) {
        //             this.model.fetch();
        //         }
        //     }
        // },
        render: function () {
            // debugger
            var that = this,
                design = this.options.design,
                tpl = this.templates[design.style];
            this.$el.addClass('shop-product-item-' + design.style);
            this.$el.html(tpl(Utils.getHBSTemplateData(this)));
            // shop pulse animation for cart button badge
            // if (this.model.hasChanged('_viewExtras') && this.model.previous('_viewExtras') && this.model.get('_viewExtras').InCartCount !== this.model.previous('_viewExtras').InCartCount)
            //     this.$('.btn.withNotificationBadge .badge').addClass("pulse").delay(1000).queue(function(){
            //         $(this).removeClass("pulse").dequeue();
            //     });
            this.$('[data-toggle="tooltip"]').tooltip();
            this.$('.product-availability .fa').popover({trigger: 'hover'});

            if (design.className) {
                this.$el.addClass(design.className);
            }

            if (this.isStyleFull()) {
                // show price chart (powered by http://omnipotent.net/jquery.sparkline)
                var prices = this.model.get('_prices') || {},
                    priceHistory = _(prices.history || {}).values(),
                    priceHistoryValuesChain = _(priceHistory).chain().pluck(1),
                    priceHistoryValues = priceHistoryValuesChain.value(),
                    priceHistoryMax = priceHistoryValuesChain.max().value(),
                    priceHistoryMin = priceHistoryValuesChain.min().value(),
                    avgMaxMin = (priceHistoryMax - priceHistoryMin) / priceHistory.length;
                if (priceHistory.length) {
                    this.$(".price-history-sparkline").sparkline(priceHistoryValues, {
                        type: 'bar',
                        // width: '300px',
                        height: '30px',
                        lineColor: '#cf7400',
                        fillColor: false,
                        chartRangeMin: priceHistoryMin - avgMaxMin,
                        drawNormalOnTop: true
                    });
                }

                ProductItem.plugin.order.dfdState.done(function () {
                    var prodQ = ProductItem.plugin.order.getProductQunatity(that.model.id);
                    var starsOdometer = new Odometer({
                        el: that.$('.add-to-cart .odometer').get(0),
                        theme: 'default',
                        value: prodQ
                    });
                    starsOdometer.render();
                    if (prodQ) {
                        that.$el.addClass('shop-product-in-cart');
                        // that.$('.add-to-cart').addClass('has-value');
                    }
                });

                this.$('.shop-single-product-thumbnails').owlCarousel({items:5, dots:false})
            }

            if (design.wrap) {
                this.$el = $(design.wrap).html(this.$el);
            }

            echo.init({
                offset: 100,
                throttle: 250,
                callback: function(element, op) {
                    console.log(op);
                    if (that.isStyleFull()) {
                        that.$('.shop-product-image-main img').magnify();
                    }
                    if (op === 'load') {
                        element.classList.add('loaded');
                    } else {
                        element.classList.remove('loaded');
                    }
                }
            });
            this.trigger('render:complete');
            this.delegateEvents();
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

            var image = null;
            if (data.Images.length) {
                image = data.Images[0].sm;
            }

            return {
                title: title || data._displayName,
                keywords: keywords || data.Synopsis,
                description: description || data.Description || data.Synopsis,
                type: 'product',
                image: image,
                url: Handlebars.helpers.bb_link(APP.instances.shop.urls.shopProduct, {asRoot: true, product: data.ExternalKey})
            };
            // seo end
        },
        switchCurrency: function (visibleCurrencyName) {
            this.$('.shop-price-value').addClass('hidden');
            this.$('.shop-price-value.' + visibleCurrencyName).removeClass('hidden');
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

    return ProductItem;

});