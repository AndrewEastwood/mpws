define([
    'jquery',
    'underscore',
    'backbone',
    'handlebars',
    'utils',
    'cachejs',
    /* model */
    'plugins/system/common/js/model/user',
    /* template */
    'text!plugins/system/site/hbs/userCreate.hbs',
    /* lang */
    'i18n!plugins/system/site/nls/translation',
    "base/js/lib/bootstrapvalidator/bootstrapValidator",
    "base/js/lib/bootstrapvalidator/validator/emailAddress",
    "base/js/lib/bootstrapvalidator/validator/notEmpty",
    "base/js/lib/bootstrapvalidator/validator/stringLength"
], function ($, _, Backbone, Handlebars, Utils, Cache, ModelAccount, tpl, lang) {

    var AccountCreate = Backbone.View.extend({
        className: 'container',
        template: Handlebars.compile(tpl), // check
        lang: lang,
        events: {
            "submit .form": 'doRegister',
        },
        initialize: function () {
            this.model = new ModelAccount();
            this.listenTo(this.model, 'change', this.render);
        },
        render: function () {
            var self = this;
            this.$el.html(this.template(Utils.getHBSTemplateData(this)));
            this.$('form').bootstrapValidator({
                message: 'Вказане значення є неправильне',
                feedbackIcons: {
                    valid: 'fa fa-check',
                    invalid: 'fa fa-ban',
                    validating: 'fa fa-refresh'
                },
                fields: {
                    FirstName: {
                        message: 'Неправильне імя',
                        validators: {
                            notEmpty: {
                                message: 'Це поле не може бути порожнім'
                            }
                        }
                    },
                    EMail: {
                        validators: {
                            notEmpty: {
                                message: 'Це поле не може бути порожнім'
                            },
                            emailAddress: {
                                message: 'Невірно введений ел. адреса'
                            }
                        }
                    }
                }
            });
            var formValidator = this.$('form').data('bootstrapValidator');
            var _userInfoChanged = _.debounce(function () {
                Cache.set("newUser", self.collectUserInfo.call(self, true));
            }, 100);
            this.$el.on('keyup', 'input', _userInfoChanged);
            // restore user info
            var newUser = Cache.get("newUser");
            var needValidate = true;
            if (!_.isEmpty(newUser)) {
                _(newUser).each(function (val, key) {
                    var _input = self.$('input[name="' + key + '"]');
                    _input.val(val);
                    needValidate = needValidate && !!val;
                });
                if (needValidate) {
                    formValidator.validate();
                }
            }
            return this;
        },
        collectUserInfo: function (skipEmptyValue) {
            var _fields = ['FirstName','LastName','EMail','Password','ConfirmPassword'];
            var _account = {};
            _(_fields).each(function (fldName) {
                var val = self.$('input[name="' + fldName + '"]').val();
                if (!skipEmptyValue || !!val) {
                    _account[fldName] = val;
                }
            });
            return _account;
        },
        clearUserInfo: function () {
            Cache.set("newUser", null);
        },
        doRegister: function () {
            var self = this;
            this.model.save(this.collectUserInfo(), {
                wait: true,
                success: function () {
                    if (self.model.isSaved()) {
                        self.clearUserInfo();
                    }
                }
            });
            return false;
        }
    });

    return AccountCreate;

});