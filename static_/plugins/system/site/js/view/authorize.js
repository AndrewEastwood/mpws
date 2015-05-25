define([
    'jquery',
    'underscore',
    'backbone',
    'handlebars',
    'utils',
    'cachejs',
    'plugins/system/common/js/model/user',
    'text!plugins/system/site/hbs/authorize.hbs',
    /* lang */
    'i18n!plugins/system/site/nls/translation',
    'toastr',
    'base/js/lib/bootstrapvalidator/bootstrapValidator',
    'base/js/lib/bootstrapvalidator/validator/emailAddress',
    'base/js/lib/bootstrapvalidator/validator/notEmpty'
], function ($, _, Backbone, Handlebars, Utils, Cache, ModelUser, tpl, lang, toastr) {

    var Authorize = Backbone.View.extend({
        lang: lang,
        template: Handlebars.compile(tpl), // check
        className: 'widget-authorize',
        id: 'widget-authorize-ID',
        events: {
            'click .register-form button': 'signUp',
            'click .login-form button': 'signIn'
        },
        initialize: function () {
            this.signUpFormValidator = null;
            // this.model = ModelUser.getInstance();
            this.model = new ModelUser();
            this.listenTo(this.model, 'sync', this.render);
            _.bindAll(this, 'signUp', 'signIn', 'render');
        },
        render: function () {
            var that = this;
            this.$el.html(this.template(Utils.getHBSTemplateData(this)));
            // this.$el = $(this.$el.html());
            if (this.$('.register-form').length) {
                // this.$('.register-form').bootstrapValidator({
                //     message: 'Вказане значення є неправильне',
                //     feedbackIcons: {
                //         valid: 'fa fa-check',
                //         invalid: 'fa fa-ban',
                //         validating: 'fa fa-refresh'
                //     },
                //     fields: {
                //         email: {
                //             validators: {
                //                 notEmpty: {
                //                     message: 'Це поле не може бути порожнім'
                //                 },
                //                 emailAddress: {
                //                     message: 'Невірно введений ел. адреса'
                //                 }
                //             }
                //         },
                //         password: {
                //             validators: {
                //                 notEmpty: {
                //                     message: 'Це поле не може бути порожнім'
                //                 }
                //             }
                //         }
                //     }
                // });
                // this.signUpFormValidator = this.$('.register-form').data('bootstrapValidator');
                var _userInfoChanged = _.debounce(function () {
                    var user = that.collectUserInfo.call(that, true);
                    console.log(user);
                    Cache.set("newUser", that.collectUserInfo.call(that, true));
                }, 100);
                this.$el.on('keyup', '.register-form input', _userInfoChanged);
                // restore user info
                var newUser = Cache.get("newUser");
                // var needValidate = true;
                if (!_.isEmpty(newUser)) {
                    _(newUser).each(function (val, key) {
                        var _input = that.$('.register-form input[name="' + key.toLowerCase() + '"]');
                        _input.val(val);
                        // needValidate = needValidate && !!val;
                    });
                    // if (needValidate) {
                    //     this.signUpFormValidator.validate();
                    // }
                }
            }
            return this;
        },
        collectUserInfo: function (skipEmptyValue) {
            var that = this;
            var _fields = ['FirstName', 'EMail'];
            var _account = {};
            _(_fields).each(function (fldName) {
                var val = that.$('.register-form input[name="' + fldName.toLowerCase() + '"]').val();
                if (!skipEmptyValue || !!val) {
                    _account[fldName] = val;
                }
            });
            return _account;
        },
        // clearUserInfo: function () {
        //     Cache.set("newUser", null);
        // },
        signUp: function (event) {
            event.preventDefault();
            var that = this;
            // debugger
            // this.signUpFormValidator.validate();
            // if (this.signUpFormValidator.isValid()) {
                this.model.save(this.collectUserInfo(), {
                    wait: true,
                    success: function (response) {
                        Cache.set("newUser", null);
                        toastr.success('Профіль створено');
                    }
                });
            // } else {
                // toastr.error('Неправильно заповнена форма');
            // }
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