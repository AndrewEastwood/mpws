define([
    'jquery',
    'underscore',
    'backbone',
    'handlebars',
    'utils',
    'plugins/system/common/js/model/user',
    'text!plugins/system/site/hbs/authorize.hbs',
    /* lang */
    'i18n!plugins/system/site/nls/translation',
    'toastr',
    'base/js/lib/bootstrapvalidator/bootstrapValidator',
    'base/js/lib/bootstrapvalidator/validator/emailAddress',
    'base/js/lib/bootstrapvalidator/validator/notEmpty'
], function ($, _, Backbone, Handlebars, Utils, ModelUser, tpl, lang, toastr) {

    var Authorize = Backbone.View.extend({
        lang: lang,
        template: Handlebars.compile(tpl), // check
        className: 'widget-authorize',
        id: 'widget-authorize-ID',
        events: {
            'submit .register-form': 'signUp',
            'submit .login-form': 'signIn'
        },
        initialize: function () {
            this.signUpFormValidator = null;
            this.model = ModelUser.getInstance();
            this.listenTo(this.model, 'change', this.render);
            _.bindAll(this, 'signUp', 'signIn');
        },
        render: function () {
            this.$el.html(this.template(Utils.getHBSTemplateData(this)));
            // this.$el = $(this.$el.html());
            this.$('.register-form').bootstrapValidator({
                message: 'Вказане значення є неправильне',
                feedbackIcons: {
                    valid: 'fa fa-check',
                    invalid: 'fa fa-ban',
                    validating: 'fa fa-refresh'
                },
                fields: {
                    email: {
                        validators: {
                            notEmpty: {
                                message: 'Це поле не може бути порожнім'
                            },
                            emailAddress: {
                                message: 'Невірно введений ел. адреса'
                            }
                        }
                    },
                    password: {
                        validators: {
                            notEmpty: {
                                message: 'Це поле не може бути порожнім'
                            }
                        }
                    }
                }
            });
            this.signUpFormValidator = this.$('.register-form').data('bootstrapValidator');
            return this;
        },
        // collectUserInfo: function (skipEmptyValue) {
        //     var _fields = ['FirstName','LastName','EMail','Password','ConfirmPassword'];
        //     var _account = {};
        //     _(_fields).each(function (fldName) {
        //         var val = self.$('input[name="' + fldName + '"]').val();
        //         if (!skipEmptyValue || !!val) {
        //             _account[fldName] = val;
        //         }
        //     });
        //     return _account;
        // },
        // clearUserInfo: function () {
        //     Cache.set("newUser", null);
        // },
        signUp: function (event) {
            event.preventDefault();
            var that = this;
            debugger
            // this.signUpFormValidator.validate();
            if (this.signUpFormValidator.isValid()) {
                this.model.save({
                    EMail: self.$('.signup-email').val(),
                    Password: self.$('.signup-password').val(),
                    ConfirmPassword: self.$('.signup-password').val()
                }, {
                    wait: true
                });
            } else {
                toastr.error('Неправильно заповнена форма');
            }
            return false;
        },
        // collectCredentials: function () {
        //     var self = this;
        //     return 
        // },
        signIn: function (event) {
            event.preventDefault();
            var that = this,
                authData = {
                    email: self.$('.signin-email').val(),
                    password: self.$('.signin-password').val(),
                    remember: self.$('.signin-rememberme').is(':checked')
                };
            Auth.signin(authData.email, authData.password, authData.remember, function (authID, response) {
                // this.render();
                that.model.set(response);
            });
            return false;
        }
    });

    return Authorize;
});