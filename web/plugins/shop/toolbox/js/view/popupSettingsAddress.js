define([
    'sandbox',
    'backbone',
    'plugins/shop/common/js/model/setting',
    'utils',
    'bootstrap-dialog',
    'bootstrap-alert',
    /* template */
    'hbs!plugins/shop/toolbox/hbs/popupSettingsAddress',
    /* lang */
    'i18n!plugins/shop/toolbox/nls/translation',
    'bootstrap-editable',
    // 'base/js/components/x-bootstrap-wysihtml5'
], function (Sandbox, Backbone, ModelSetting, Utils, BootstrapDialog, BSAlerts, tpl, lang, dfdEditable, dfdXEditWysi) {

    var PopupSettingsAddress = Backbone.View.extend({
        template: Handlebars.compile(tpl), // check
        lang: lang,
        events: {
            'keypress #js-ShopName': 'updateTitle',
            'keydown #js-ShopName': 'updateTitle',
            'keyup #js-ShopName': 'updateTitle'
        },
        initialize: function (options) {
            var that = this;
            this.options = options || {};
            if (!this.model) {
                this.model = new ModelSetting();
                this.model.setType('ADDRESS');
            }
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
            this.listenTo(this.model, 'sync', this.render);
            // setup this view as dialog
            this.$dialog = new BootstrapDialog({
                closable: false,
                message: this.$el,
                cssClass: 'popup-settings-address',
                onhide: function () {
                    that.stopListening(that.model);
                },
                buttons: [{
                    label: lang.popup_origin_button_Close,
                    cssClass: 'btn-default btn-link',
                    action: function (dialog) {
                        dialog.close();
                    }
                }, {
                    label: lang.popup_origin_button_Save,
                    cssClass: 'btn-success btn-outline',
                action: function (dialog) {
                        that.model.set({
                            ShopName :that.$('#js-ShopName').val(),
                            Country :that.$('#js-Country').val(),
                            City :that.$('#js-City').val(),
                            AddressLine1 :that.$('#js-AddressLine1').val(),
                            AddressLine2 :that.$('#js-AddressLine2').val(),
                            AddressLine3 :that.$('#js-AddressLine3').val(),
                            PhoneHotline: that.$('#js-PhoneHotline').val(),
                            MapUrl: that.$('#js-MapUrl').val(),
                            SocialFacebook: that.$('#js-SocialFacebook').val(),
                            SocialTwitter: that.$('#js-SocialTwitter').val(),
                            SocialLinkedIn: that.$('#js-SocialLinkedIn').val(),
                            SocialGooglePlus: that.$('#js-SocialGooglePlus').val(),
                            Phone1Label: that.$('#js-Phone1Label').val(),
                            Phone1Value: that.$('#js-Phone1Value').val(),
                            Phone2Label: that.$('#js-Phone2Label').val(),
                            Phone2Value: that.$('#js-Phone2Value').val(),
                            Phone3Label: that.$('#js-Phone3Label').val(),
                            Phone3Value: that.$('#js-Phone3Value').val(),
                            Phone4Label: that.$('#js-Phone4Label').val(),
                            Phone4Value: that.$('#js-Phone4Value').val(),
                            Phone5Label: that.$('#js-Phone5Label').val(),
                            Phone5Value: that.$('#js-Phone5Value').val(),
                            HoursMonday: that.$('#js-hours-monday').editable('getValue', true),
                            HoursTuesday: that.$('#js-hours-tuesday').editable('getValue', true),
                            HoursWednesday: that.$('#js-hours-wednesday').editable('getValue', true),
                            HoursThursday: that.$('#js-hours-thursday').editable('getValue', true),
                            HoursFriday: that.$('#js-hours-friday').editable('getValue', true),
                            HoursSturday: that.$('#js-hours-sturday').editable('getValue', true),
                            HoursSunday: that.$('#js-hours-sunday').editable('getValue', true),
                            InfoPayment: that.$('#js-info-InfoPayment').editable('getValue', true),
                            InfoShipping: that.$('#js-info-InfoShipping').editable('getValue', true),
                            InfoWarranty: that.$('#js-info-InfoWarranty').editable('getValue', true),
                        });
                        that.model.save().then(function () {
                            BSAlerts.success(lang.settings_message_success);
                        }, function () {
                            BSAlerts.danger(lang.settings_error_save);
                            that.model.set(that.model.previousAttributes());
                            that.render();
                        });
                    }
                }]
            });
            _.bindAll(this, 'updateTitle');
        },
        updateTitle: function () {
            var $title = $('<span/>'),
                shopName = this.$('#js-ShopName').val();
                // debugger
            if (this.model.isNew()) {
                $title.append([$('<i/>').addClass('fa fa-fw fa-asterisk'), shopName || lang.popup_settingAddress_title_new]);
            } else {
                $title.append([$('<i/>').addClass('fa fa-fw fa-pencil'), shopName || lang.popup_settingAddress_title_edit]);
            }
            this.$dialog.setTitle($title);
        },
        render: function () {
            var that = this,
                tplData = Utils.getHBSTemplateData(this);
            tplData.data = _(tplData.data).omit('ID', 'errors', 'success');
            this.$el.html(tpl(tplData));
            // set up open hours editables
            this.$('#openhours .ediatble').editable(_.defaults({
                emptytext: '00:00 - 00:00',
                mode: 'inline'
            }, this.options.editableOptions));
            // set up wyswig
            dfdXEditWysi.done(function () {
                that.$('.wysihtml5').editable(_.defaults({
                    mode: 'inline',
                    emptytext: 'введіть текст'
                }, that.options.editableOptions));
            });
            if (!this.$dialog.isOpened()) { 
                this.$dialog.open();
            }
            this.updateTitle();
            return this;
        }
    });

    return PopupSettingsAddress;

});