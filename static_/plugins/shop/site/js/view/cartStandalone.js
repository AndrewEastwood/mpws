define([
    'underscore',
    'backbone',
    'handlebars',
    'plugins/shop/site/js/model/order',
    'bootstrap-dialog',
    'utils',
    'cachejs',
    'text!plugins/shop/site/hbs/cartStandalone.hbs',
    'text!plugins/shop/site/hbs/cartSuccess.hbs',
    /* lang */
    'i18n!plugins/shop/site/nls/translation',
    'jquery.cookie',
    "select2",
    "base/js/lib/bootstrapvalidator/bootstrapValidator",
    "base/js/lib/bootstrapvalidator/validator/emailAddress",
    "base/js/lib/bootstrapvalidator/validator/phone",
    "base/js/lib/bootstrapvalidator/validator/regexp",
    "base/js/lib/bootstrapvalidator/validator/notEmpty",
    "base/js/lib/bootstrapvalidator/validator/stringLength",
    'jquery.maskedinput'
], function (_, Backbone, Handlebars, ModelOrder, BootstrapDialog, Utils, Cache, tpl, tplSuccess, lang) {

    var CartStandalone = Backbone.View.extend({
        className: 'shop-cart-standalone',
        templates: {
            cart: Handlebars.compile(tpl), // check
            success: Handlebars.compile(tplSuccess)
        },
        lang: lang,
        events: {
            'click .shop-cart-product-quantity a': 'updateQuantity',
            'click .shop-cart-do-checkout': 'doCheckout',
            'click .shop-cart-do-edit': 'doEdit',
            'click .shop-cart-do-preview': 'doPreview',
            'click .shop-cart-do-save': 'doSave',
            'click .shop-cart-product-remove': 'removeProduct',
            'click .shop-cart-clear': 'clearAll',
        },
        initialize: function (options) {
            this.model = ModelOrder.getInstance();
            this.modelSettings = options && options.settings || null;
            if (this.modelSettings) {
                this.listenTo(this.modelSettings, 'change', this.render);
            }
            this.listenTo(this.model, 'sync', this.render);
            this.listenTo(this.model, 'saved', this.renderSuccess);
            this.listenTo(this.model, 'errors', this.render);
            _.bindAll(this, 'doCheckout', 'doPreview', 'renderSuccess', 'saveUserInfo', 'clearUserInfo', 'collectUserInfo');
        },
        render: function () {
            // debugger
            // console.log('rendering cart standalone');
            var self = this;
            var data = Utils.getHBSTemplateData(this);
            var formValidator = null;

            if (this.modelSettings) {
                data.extras.settings = this.modelSettings.toSettings();
            }

            this.$el.off().empty().html(this.templates.cart(data));

            // save user info
            var _userInfoChanged = _.debounce(this.saveUserInfo, 100);
            this.$el.on('keypress', 'input[type="text"],textarea', _userInfoChanged);
            this.$el.on('click', 'input[type="checkbox"]', _userInfoChanged);
            this.$el.on('change', 'select', _userInfoChanged);
            this.$el.on('click', '.btn-promo-submit', function () {
                self.model.applyPromo(self.$('#shop-order-promo-ID').val());
            });
            this.$el.on('click', '.btn-promo-cancel', function () {
                self.model.applyPromo(false);
            });
            this.$('[data-toggle="tooltip"]').tooltip();
            // restore user info
            var _shopUser = Cache.get("shopUser");
            if (_shopUser) {
                _(_shopUser).each(function (val, key) {
                    // debugger;
                    var _input = self.$('[name="' + key + '"]');

                    if (_input.is(':checkbox'))
                        _input.prop('checked', !!val);
                    else
                        _input.val(val);
                });
            }

            this.$('select').select2();
            this.$('input[name="shopCartUserPhone"]').mask('(999) 999-99-99');

            var account = APP.hasPlugin('account') && this.model.has('account') && this.model.get('account').ID;
            // if we have saved order we clear user data
            // if we have account plugin
            // debugger;
            if (account) {
                // account is signed in
                this.$('#account-addresses-ID').on('change', function (event) {
                    if ($(this).val())
                        self.$('.form-group-address, .form-group-pobox, .form-group-country, .form-group-city').prop('disable', true).addClass('hide');
                    else
                        self.$('.form-group-address, .form-group-pobox, .form-group-country, .form-group-city').prop('disable', false).removeClass('hide');
                });
                self.$('#account-addresses-ID').trigger('change');
            }

            this.$('#shopping-cart-logistic-ID').on('change', function (event) {
                if ($(this).val() !== "")
                    self.$('.form-group-warehouse').prop('disable', false).removeClass('hide');
                else
                    self.$('.form-group-warehouse').prop('disable', true).addClass('hide');
            }).trigger('change');

            var $form = this.$('.form-order-create');
            var $formPreview = this.$('.form-order-preview');

            $formPreview.on('submit', function () {
                return false;
            });

            $form.bootstrapValidator({
                message: 'Вказане значення є неправильне',
                feedbackIcons: {
                    valid: 'fa fa-check',
                    invalid: 'fa fa-ban',
                    validating: 'fa fa-refresh'
                },
                fields: {
                    shopCartUserName: {
                        message: 'Неправильне імя',
                        validators: {
                            notEmpty: {
                                message: 'Це поле не може бути порожнім'
                            }
                        }
                    },
                    shopCartUserEmail: {
                        validators: {
                            notEmpty: {
                                message: 'Це поле не може бути порожнім'
                            },
                            emailAddress: {
                                message: 'Невірно введений ел. адреса'
                            }
                        }
                    },
                    shopCartUserPhone: {
                        validators: {
                            notEmpty: {
                                message: 'Це поле не може бути порожнім'
                            },
                            regexp: {
                                message: "Не правильний формат телефону",
                                regexp: /\(\d{3}\)\s\d{3}-\d{2}-\d{2}/
                            }
                        }
                    },
                    shopCartUserAddress: {
                        validators: {
                            notEmpty: {
                                message: 'Це поле не може бути порожнім'
                            }
                        }
                    },
                    shopCartUserPOBox: {
                        validators: {
                            notEmpty: {
                                message: 'Це поле не може бути порожнім'
                            }
                        }
                    },
                    shopCartUserCountry: {
                        validators: {
                            notEmpty: {
                                message: 'Це поле не може бути порожнім'
                            }
                        }
                    },
                    shopCartUserCity: {
                        validators: {
                            notEmpty: {
                                message: 'Це поле не може бути порожнім'
                            }
                        }
                    },
                    shopCartWarehouse: {
                        validators: {
                            notEmpty: {
                                message: 'Це поле не може бути порожнім'
                            }
                        }
                    }
                }
            });
            this.formValidator = $form.data('bootstrapValidator');

            this.delegateEvents();
            return this;
        },
        // render page when order cretaed
        renderSuccess: function (modelData) {
            var data = Utils.getHBSTemplateData(this);
            this.clearUserInfo();
            this.$el.off().empty().html(this.templates.success(data));
            return this;
        },
        // remove product from cart
        removeProduct: function (event) {
            var that = this,
                $target = $(event.target).parents('a'),
                productID = $target.data('id');
            BootstrapDialog.confirm('Видалити цей товар?', function (result) {
                if (result) {
                    that.model.removeProduct(productID);
                }
            });
        },
        // truncate shopping cart
        clearAll: function () {
            var that = this;
            BootstrapDialog.confirm('Видалити всі товари з кошика?', function (result) {
                if (result) {
                    that.model.setProduct('*', 0, true);
                }
            });
        },
        // naigate to edit page
        doEdit: function () {
            this.$('.shop-cart-page').addClass('hidden');
            this.$('.shop-cart-edit').removeClass('hidden');
        },
        // navigate to checkout page
        doCheckout: function () {
            this.$('.shop-cart-page').addClass('hidden');
            this.$('.shop-cart-checkout').removeClass('hidden');
        },
        // navigate to save page
        doSave: function () {
            var $form = this.$('.form-order-create');
            var result = {},
                formDataArray = $form.serializeArray();
            _(formDataArray).each(function (item) {
                result[item.name] = item.value;
            });
            result.customerCurrencyName = APP.instances.shop.settings._user.activeCurrency;
            this.model.saveOrder(result);
        },
        // navigate to preview page
        doPreview: function () {
            var that = this,
                bvOptions = that.formValidator.getOptions() || {};
            that.$('form.form-order-create .form-control').each(function () {
                var fldName = $(this).attr('name');
                if (fldName && bvOptions.fields && bvOptions.fields[fldName]) {
                    that.formValidator.revalidateField(fldName);
                }
            });
            if (this.formValidator.isValid()) {
                that.$('form.form-order-create .form-control').each(function () {
                    var fldName = $(this).attr('name');
                    var value = $(this).find('option:selected').text() || $(this).text() || $(this).val();
                    // console.log(fldName + ' = ' + value);
                    if (fldName) {
                        that.$('form.form-order-preview').find('.form-control[name="' + fldName + '"]').text(value);
                    }
                });
                that.$('.shop-cart-page').addClass('hidden');
                that.$('.shop-cart-preview').removeClass('hidden');
            }
        },
        // set new qunatity for specific product
        updateQuantity: function (e) {
            // Quantity Spinner
            e.preventDefault();
            var $targetBtn = $(e.target),
                $qInput = $targetBtn.parent().find('input[name="quantity"]'),
                productID = $qInput.data('id'),
                currentQty = parseInt($qInput.val(), 10);
            if ($targetBtn.hasClass('shop-cart-product-minus') && currentQty > 1) {
                $qInput.val(--currentQty);
            } else if($targetBtn.hasClass('shop-cart-product-plus') && currentQty < 99) {
                $qInput.val(++currentQty);
            }
            this.updateModelQuantity(productID, currentQty);
        },
        updateModelQuantity: _.debounce(function (productID, currentQty) {
            this.model.setProduct(productID, currentQty, true);
        }, 250),
        // manage user's data for order form
        collectUserInfo: function () {
            // collect user info
            var _userInfo = {};
            this.$('input,textarea,select').not('disable').each(function () {
                if (!/^shopCart/.test($(this).attr('name')))
                    return;
                if ($(this).is(':checkbox'))
                    _userInfo[$(this).attr('name')] = $(this).is(':checked');
                else
                    _userInfo[$(this).attr('name')] = $(this).val();
            });
            return _userInfo;
        },
        saveUserInfo: function () {
            Cache.set("shopUser", this.collectUserInfo(this));
        },
        clearUserInfo: function () {
            Cache.set("shopUser", null);
        }
    });

    return CartStandalone;

});