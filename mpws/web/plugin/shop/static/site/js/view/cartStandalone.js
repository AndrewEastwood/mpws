define("plugin/shop/site/js/view/cartStandalone", [
    'default/js/lib/sandbox',
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'default/js/lib/utils',
    'default/js/lib/cache',
    'default/js/plugin/hbs!plugin/shop/site/hbs/cartStandalone',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/site/nls/translation',
    "default/js/lib/jquery.cookie",
    "default/js/lib/select2/select2",
    "default/js/lib/fuelux/wizard",
    "default/js/lib/bootstrapvalidator/bootstrapValidator",
    "default/js/lib/bootstrapvalidator/validator/emailAddress",
    "default/js/lib/bootstrapvalidator/validator/phone",
    "default/js/lib/bootstrapvalidator/validator/regexp",
    "default/js/lib/bootstrapvalidator/validator/notEmpty",
    "default/js/lib/bootstrapvalidator/validator/stringLength",
    'default/js/lib/jquery.maskedinput'
], function (Sandbox, _, Backbone, Utils, Cache, tpl, lang) {

    // $.cookie.json = true;

    var CartStandalone = Backbone.View.extend({
        className: 'row shop-cart-standalone',
        id: 'shop-cart-standalone-ID',
        template: tpl,
        lang: lang,
        initialize: function() {
            this.listenTo(this.model, 'change', this.render);
            // if (APP.hasPlugin('account')) {
            //     var _accountModel = Cache.getObject('account:model');
            //     if (_accountModel)
            //         this.listenTo(_accountModel, 'change', this.render);
            // }
        },
        updateProductQuantity: function (event) {
            var $input = this.$(event.target);
            var elementData = $input.data();
            if (this.model.getProductByID(elementData.id) && $input.val())
                this.model.setProductQuantity(elementData, elementData.id, $input.val());
        },
        collectUserInfo: function () {
            // collect user info
            // debugger;
            var _userInfo = {};
            this.$('input,textarea,select').not('disable').each(function(){
                if (!/^shopCart/.test($(this).attr('name')))
                    return;
                if ($(this).is(':checkbox'))
                    _userInfo[$(this).attr('name')] = $(this).is(':checked');
                else
                    _userInfo[$(this).attr('name')] = $(this).val();
            });
            return _userInfo;
        },
        clearUserInfo: function () {
            Cache.setCookie("shopUser", null);
        },
        render: function () {
            var self = this;
            // debugger;
            var data = Utils.getHBSTemplateData(this);
            this.$el.off().empty().html(this.template(data));
            // save user info
            var _userInfoChanged = _.debounce(function () {
                Cache.setCookie("shopUser", self.collectUserInfo.call(self));
            }, 100);
            var _productQunatityChanged = _.debounce(function (event) {
                self.updateProductQuantity.call(self, event);
            }, 300);
            this.$el.on('keypress', 'input[type="text"],textarea', _userInfoChanged);
            this.$el.on('click', 'input[type="checkbox"]', _userInfoChanged);
            this.$el.on('change', 'select', _userInfoChanged);
            this.$el.on('change', 'input.quantity', _productQunatityChanged);
            this.$el.on('click', '.btn-promo-submit', function() {
                self.model.applyPromo(self.$('#shop-order-promo-ID').val());
            });
            this.$el.on('click', '.btn-promo-cancel', function() {
                self.model.applyPromo(false);
            });
            this.$('[data-toggle="tooltip"]').tooltip();
            // restore user info
            var _shopUser = Cache.getCookie("shopUser");
            if (_shopUser)
                _(_shopUser).each(function(val, key){
                    // debugger;
                    var _input = self.$('[name="' + key + '"]');

                    if (_input.is(':checkbox'))
                        _input.prop('checked', !!val);
                    else
                        _input.val(val);
                });

            this.$('select').select2();
            this.$('input[name="shopCartUserPhone"]').mask('(999) 999-99-99');


            var account = APP.hasPlugin('account') && this.model.has('account') && this.model.get('account').ID;
            // if we have saved order we clear user data
            // if we have account plugin
            // debugger;
            if (account) {
                // account is signed in
                this.$('#account-profile-addresses-ID').on('change', function (event) {
                    if ($(this).val())
                        self.$('.form-group-address, .form-group-pobox, .form-group-country, .form-group-city').prop('disable', true).addClass('hide');
                    else
                        self.$('.form-group-address, .form-group-pobox, .form-group-country, .form-group-city').prop('disable', false).removeClass('hide');
                });
                self.$('#account-profile-addresses-ID').trigger('change');
            }

            this.$('#shopping-cart-logistic-ID').on('change', function (event) {
                if ($(this).val())
                    self.$('.form-group-warehouse').prop('disable', false).removeClass('hide');
                else
                    self.$('.form-group-warehouse').prop('disable', true).addClass('hide');
            });

            var $form = this.$('.form-order-create');
            var $formPreview = this.$('.form-order-preview');

            $formPreview.on('submit', function () {
                return false;
            })

            this.$('.button-order-back').click(function(){
                self.$('.wizard').wizard('back');
            });

            this.$('.button-order-preview').click(function(){
                var formValidator = $form.data('bootstrapValidator');
                formValidator.validate();
                if (formValidator.isValid()) {
                    self.$('form.form-order-create .form-control').each(function(){
                        var fldName = $(this).attr('name');
                        var value = $(this).find('option:selected').text() || $(this).text() || $(this).val();
                        if (fldName)
                            self.$('form.form-order-preview').find('.form-control[name="' + fldName + '"]').text(value);
                    });
                    self.$('.wizard').wizard('next');
                }
            });

                // _(​formDataArray).each();
            this.$('.button-order-save').click(function(){
                var result = {};
                var formDataArray = $form.serializeArray();
                _(formDataArray).each(function(item){
                    result[item.name] = item.value;
                });
                self.model.saveOrder(result);
                // ({
                //     post: true,
                //     success: function (model, response) {
                //         if (response) {
                //             if (response.ok) {
                //                 self.$('.wizard').wizard('next');
                //             } else if (response.error) {
                //                 // show error
                //             }
                //         }
                //     }
                // });
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
                            },
                            regexp: {
                                regexp: /^[a-zA-Z0-9_]+$/,
                                message: 'Недопустимі символи в полі (дозволено букви, цифри та нижнє підкреслення)'
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


            return this;
        }
    });

    return CartStandalone;

});