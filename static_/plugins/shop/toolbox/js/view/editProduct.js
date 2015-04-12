define([
    'jquery',
    'backbone',
    'handlebars',
    'plugins/shop/toolbox/js/model/product',
    'utils',
    'cachejs',
    'bootstrap-dialog',
    'bootstrap-alert',
    "formatter-price",
    "image-upload",
    /* template */
    'text!plugins/shop/toolbox/hbs/editProduct.hbs',
    /* lang */
    'i18n!plugins/shop/toolbox/nls/translation',
    'select2',
    // 'typeahead',
    'jquery.maskmoney',
    'bootstrap-tagsinput',
    'bootstrap-editable',
    'jquery.sparkline'
], function ($, Backbone, Handlebars, ModelProduct, Utils, Cache, BootstrapDialog, BSAlert, priceFmt, WgtImageUpload, tpl, lang) {

    function _getTitle (isEdit) {
        if (isEdit) {
            return $('<span/>').addClass('fa fa-pencil').append(' ', lang.popup_product_title_edit);
        } else {
            return $('<span/>').addClass('fa fa-asterisk').append(' ', lang.popup_product_title_new);
        }
    }

    var EditProduct = Backbone.View.extend({
        template: Handlebars.compile(tpl), // check
        lang: lang,
        className: 'bootstrap-dialog type-primary size-normal plugin-shop-product',
        events: {
            'click .del-image': 'removeImage',
            'click .restore-image': 'restoreImage',
            'click .add-feature': 'addFeature',
            'click .remove-feature': 'removeFeature',
            'click .feature-types a': 'selectFeatureGroup',
        },
        initialize: function () {
            this.model = new ModelProduct();
            this.listenTo(this.model, 'sync', this.render);
            this.options = {};
            this.options.editableOptions = {
                mode: 'popup',
                name: 'Name',
                savenochange: true,
                unsavedclass: '',
                validate: function (value) {
                    if ($.trim(value) === '') {
                        return 'Введіть значення';
                    }
                }
            };
        },
        render: function () {
            var that = this;
            var $dialog = new BootstrapDialog({
                closable: false,
                draggable: false,
                title: _getTitle(that.model.id),
                message: $(this.template(Utils.getHBSTemplateData(that))),
                buttons: [{
                    label: lang.popup_product_button_Close,
                    cssClass: 'btn-default btn-link',
                    action: function (dialog) {
                        // dialog.close();
                        Backbone.history.navigate(APP.instances.shop.urls.contentList, true);
                    }
                }, {
                    label: lang.popup_product_button_Save,
                    cssClass: 'btn-success btn-outline',
                    action: function (dialog) {
                        that.model.save({
                            CategoryID: parseInt(that.$('#category').select2('val'), 10),
                            OriginID: parseInt(that.$('#origin').select2('val'), 10),
                            Name: that.$('#name').val(),
                            Model: that.$('#model').val(),
                            Price: parseFloat(that.$('#price').val().replace( /^\D+/g, ''), 10),
                            Synopsis: that.$('#synopsis').val(),
                            Description: that.$('#description').val(),
                            IsPromo: that.$('#ispromo').is(':checked'),
                            IsOffer: that.$('#isoffer').is(':checked'),
                            IsFeatured: that.$('#isfeatured').is(':checked'),
                            Tags: that.$('#tags').val(),
                            ISBN: that.$('#isbn').val(),
                            Warranty: that.$('#warranty').val(),
                            Features: that.getFeaturesMap(),
                            ShowBanner: that.$('#showBanners').is(':checked'),
                            Status: that.$('input[name="Status"]:checked').val(),
                            file1: that.$('#file1').val(),
                            file2: that.$('#file2').val(),
                            file3: that.$('#file3').val(),
                            file4: that.$('#file4').val(),
                            file5: that.$('#file5').val(),
                            promoText: that.$('#promotext').val(),
                            fileBannerLarge: that.$('#bannerLarge').val(),
                            fileBannerMedium: that.$('#bannerMedium').val(),
                            fileBannerSmall: that.$('#bannerSmall').val(),
                            fileBannerMicro: that.$('#bannerMicro').val(),
                            bannerTextLine1: that.$('#bannerTextLine1').val(),
                            bannerTextLine2: that.$('#bannerTextLine2').val(),
                        }, {
                            silent: true,
                            success: function (model, response) {
                                if (!response || !response.success) {
                                    that.render();
                                    BSAlert.danger('Помилка під час оновлення замовлення');
                                } else {
                                    if (dialog.getData('isNew')) {
                                        BSAlert.success('Товар успішно створено');
                                    } else {
                                        BSAlert.success('Товар успішно оновлено');
                                    }
                                    // dialog.close();
                                    Backbone.history.navigate(APP.instances.shop.urls.contentList, true);
                                }
                            },
                            error: function () {
                                BSAlert.danger('Помилка під час оновлення замовлення');
                            }
                        });
                    }
                }]
            });

            $dialog.realize();
            $dialog.updateTitle();
            $dialog.updateMessage();
            $dialog.updateClosable();

            this.$el.html($dialog.getModalContent());

            var _initCategory = {
                category: this.model.get('_category') || {}
            };
            var _initOrigin = {
                origin: this.model.get('_origin') || {}
            };
            if (!this.model.isNew()) {
                _initCategory = {
                    ID: _initCategory.category.ID || null,
                    Text: _initCategory.category.Name || null
                };
                _initOrigin = {
                    ID: _initOrigin.origin.ID || null,
                    Text: _initOrigin.origin.Name || null
                };
            } else {
                _initCategory = Cache.getOnce('mpwsShopPopupProductInitCategory') || _initCategory;
                _initOrigin = Cache.getOnce('mpwsShopPopupProductInitOrigin') || _initOrigin;

                if (_.isString(_initCategory)) {
                    _initCategory = _initCategory.split(';;');
                    if (_initCategory.length === 2) {
                        _initCategory = {
                            ID: parseInt(_initCategory[0], 10),
                            Text: _initCategory[1]
                        };
                    }
                }
                if (_.isString(_initOrigin)) {
                    _initOrigin = _initOrigin.split(';;');
                    if (_initOrigin.length === 2) {
                        _initOrigin = {
                            ID: parseInt(_initOrigin[0], 10),
                            Text: _initOrigin[1]
                        };
                    }
                }
            }
            var _selectCategory = this.$('#category').select2({
                placeholder: _initCategory.ID ? false : 'Виберіть категорію',
                ajax: {
                    url: APP.getApiLink('shop', 'categories'),
                    results: function (data, page) {
                        var _results = _(data.items).map(function (item) {
                            return {
                                id: item.ID,
                                text: item.Name
                            };
                        });
                        return {
                            results: _results
                        };
                    }
                },
                initSelection: function (element, callback) {
                    if (_initCategory.ID >= 0) {
                        callback({
                            id: _initCategory.ID,
                            text: _initCategory.Text
                        });
                    }
                }
            });
            var _selectOrigins = this.$('#origin').select2({
                placeholder: _initOrigin.ID ? false : 'Виберіть виробника',
                ajax: {
                    url: APP.getApiLink('shop','origins'),
                    results: function (data, page) {
                        var _results = _(data.items).map(function (item) {
                            return {
                                id: item.ID,
                                text: item.Name
                            };
                        });
                        return {
                            results: _results
                        };
                    }
                },
                initSelection: function (element, callback) {
                    if (_initOrigin.ID >= 0) {
                        callback({
                            id: _initOrigin.ID,
                            text: _initOrigin.Text
                        });
                    }
                }
            });

            if (_initOrigin.ID >= 0) {
                this.$('#origin').select2('val', _initOrigin.ID);
            }
            if (_initCategory.ID >= 0) {
                this.$('#category').select2('val', _initCategory.ID);
            }

            // configure price display
            // var _currencyDisplay = APP.instances.shop.settings.MISC.DBPriceCurrencyType,
            //     fmt = priceFmt(0, _currencyDisplay, APP.instances.shop.settings.EXCHANAGERATESDISPLAY),
            //     precision = fmt.match(/\.(0+)/),
            //     items = fmt.split('0.00');
                // debugger
            // var _options = {
            //     thousands: '',
            //     decimal: '.',
            //     // precision: precision && precision.length === 2 ? precision[1].length : 2,
            //     // prefix: items && items[0] || '',
            //     // suffix: items && items.length > 1 && items[2] || ''
            // };
            // debugger
            // var _currencyDisplay = APP.instances.shop.settings.MISC.DBPriceCurrencyType;
            // if (_currencyDisplay) {
            //     if (_currencyDisplay.showBeforeValue) {
            //         _options.prefix = _currencyDisplay.text
            //     } else {
            //         _options.suffix = _currencyDisplay.text;
            //     }
            // }

            // this.$('#price').maskMoney(_options);
            // this.$('#price').maskMoney('mask');

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
            this.$('#tags').tagsinput();

            var features = this.model.get('Features');
            if (features) {
                that.$('.features .features-list').each(function () {
                    var groupFeatureItems = features[$(this).attr('name')];
                    if (groupFeatureItems) {
                        $(this).val(_(groupFeatureItems).values().join(','));
                        $(this).tagsinput();
                    }
                });
            }

            // >> let's govnokod begin
            $.get(APP.getApiLink('shop','productfeatures'), function (allfeatures) {
                var featureTypes = _(allfeatures).keys();
                // var featureItems = _(allfeatures).reduce(function (memo, list) { var items = _(list).values(); return _(memo.concat(items)).uniq(); }, []);
                if (featureTypes && featureTypes.length) {
                    that.$('.feature-item .dropdown-toggle').removeClass('hidden');
                    that.$('.feature-item .feature-types').removeClass('hidden');
                    _(featureTypes).each(function (groupName) {
                        that.$('.feature-item .feature-types').append('<li><a href="javascript://" class="feature-type">' + groupName + '</a></li>');
                    })
                }
            });
            // too much govna at this point

            // setup logo upload
            var logoUpload = new WgtImageUpload({
                el: this.$el,
                selector: '.temp-upload-image'
            });
            logoUpload.render();


            // this.setupFileUploadItem(this.$('.temp-upload-image'));

            return this;
        },
        getFeaturesMap: function () {
            var map = {};
            this.$('.features .feature-item .form-control').each(function () {
                map[$(this).attr('name')] = $(this).val();
            });
            return map;
        },
        addFeature: function (event) {
            event.preventDefault();
            var that = this;
            var $tpl = this.$('.hidden .feature-template').clone();
            $tpl.removeClass('.feature-template');
            this.$('.features').append($tpl);
            var $buttonLabel = $tpl.find('.button-label');
            var $control = $tpl.find('.form-control');
            $buttonLabel.editable(this.options.editableOptions)
                .on('save', function (e, params) {
                    $control.attr('name', params.newValue);
                    that.updateFeatureGroupNames();
                });

            $tpl.find('.features-list').tagsinput();
        },
        removeFeature: function (event) {
            event.preventDefault();
            var $featureItem = $(event.target).closest('.feature-item');
            $featureItem.remove();
        },
        updateFeatureGroupNames: _.debounce(function () {
            var $allGroupsMenuItems = this.$('.features .feature-item .feature-types .feature-type');
            var $allCurrentNames = this.$('.features .feature-item .button-label');
            var names = [];
            $allCurrentNames.each(function () {
                names.push($(this).text());
            });
            $allGroupsMenuItems.each(function () {
                names.push($(this).text());
            });

            names = _(names).uniq();

            this.$('.feature-item .feature-types').empty();
            this.$('.feature-item .feature-types').each(function () {
                var $featureMenu = $(this);
                _(names).each(function (v) {
                    $featureMenu.append($('<li>').append($('<a>').attr({
                        href: 'javascript://'
                    }).addClass('feature-type').text(v)));
                });
            });

            if (names.length) {
                this.$('.dropdown-toggle').removeClass('hidden');
                this.$('.feature-types').removeClass('hidden');
            }
        }, 200),
        selectFeatureGroup: function (event) {
            event.preventDefault();

            var $menuItem = $(event.target);
            var $featureItem = $menuItem.closest('.feature-item');
            var $control = $featureItem.find('.form-control');
            var $buttonLabel = $featureItem.find('.button-label');
            var concept = $menuItem.text();
            $control.attr('name', concept);
            $buttonLabel.text(concept);
            $buttonLabel.editable(this.options.editableOptions)
                .on('save', function (e, params) {
                    $menuItem.text(params.newValue);
                    $control.attr('name', params.newValue);
                });
        }
    });

    return EditProduct;
});