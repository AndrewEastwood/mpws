define([
    'backbone',
    'handlebars',
    'plugins/shop/common/js/model/setting',
    'utils',
    'bootstrap-dialog',
    'toastr',
    /* template */
    'text!plugins/shop/toolbox/hbs/settingsAlerts.hbs',
    'text!plugins/shop/toolbox/hbs/settingsAlertPopupContent.hbs',
    /* lang */
    'i18n!plugins/shop/toolbox/nls/translation',
    'bootstrap-switch'
], function (Backbone, Handlebars, ModelSetting, Utils, BootstrapDialog, toastr, tpl, tplParams, lang) {

    return Backbone.View.extend({
        className: "panel panel-default shop-settings-alerts",
        template: Handlebars.compile(tpl), // check
        templatePopup: Handlebars.compile(tplParams), // check
        lang: lang,
        events: {
            'switchChange.bootstrapSwitch .switcher': 'setSettingState',
            'click .edit-alert': 'editAlert'
        },
        initialize: function () {
            this.options = {};
            this.options.switchOptions = {
                onColor: 'success',
                size: 'mini',
                onText: '<i class="fa fa-check fa-fw"></i>',
                offText: '<i class="fa fa-times fa-fw"></i>'
            };
            this.model = new ModelSetting();
            this.model.setType('ALERTS');
            this.listenTo(this.model, 'sync', this.render);
            _.bindAll(this, 'toggleAlerts', 'render', 'editAlert');
        },
        render: function () {
            var tplData = Utils.getHBSTemplateData(this);
            tplData.data = _(tplData.data).omit('ID', 'errors', 'success');
            tplData.data = _(tplData.data).pick(function (v, k) { return !/^Param/.test(k); });
            // debugger
            this.$el.html(this.template(tplData));
            this.$('.switcher-main').html(this.$('.panel-body .shop-property-AllowAlerts').clone(true));
            this.$('.panel-body .shop-property-AllowAlerts').remove();
            this.$('.switcher:visible').bootstrapSwitch(this.options.switchOptions);
            this.toggleAlerts();
            return this;
        },
        toggleAlerts: function () {
            this.$('.panel-body .list-group-item').toggleClass('disabled', !this.model.get('AllowAlerts'));
            this.$('.panel-body .switcher').bootstrapSwitch('disabled', !this.model.get('AllowAlerts'));
        },
        setSettingState: function (event, state) {
            var that = this,
                $item = $(event.target).closest('.list-group-item'),
                propName = $item.data('property');
            this.model.set(propName, !!state);
            this.model.save().then(this.render, function () {
                toastr.danger(lang.settings_error_save);
                that.model.set(that.model.previousAttributes());
                that.render();
            });
        },
        editAlert: function (event) {
            var that = this,
                $item = $(event.target).closest('.list-group-item'),
                propName = $item.data('property'),
                alertPropertyName = 'Params' + propName,
                alertPropertyContent = this.model.get(alertPropertyName) || '',
                $popupTpl = $(this.templatePopup());
                $popupTpl.find('textarea').text(alertPropertyContent);

            BootstrapDialog.show({
                title: 'Сповіщення',
                message: $popupTpl,//$ediatble,
                cssClass: 'popup-settings-alerts',
                onshow: function (dialog) {
                    var $txtArea = dialog.getMessage().find('textarea');
                    dialog.getMessage().find('.label').addClass('label-success').on('click', function () {
                        var eparam = $(this).data('eparam'),
                            defValue = eparam.indexOf('=') >= 0 ? '' : 'XXXXXXX';
                        if (!eparam) {
                            return;
                        }
                        $txtArea.val($txtArea.val() + ' [' + eparam + defValue + '];\r\n');
                        // if (eparam.indexOf('=') >= 0) {
                        // } else {
                        //     $txtArea.val($txtArea.val() + ' [' +  + '];\r\n');
                        // }
                    });
                },
                onhide: function () {
                    that.stopListening(that.model);
                },
                buttons: [{
                    label: lang.popups.settingsAlerts.buttonClose,
                    cssClass: 'btn-default btn-link',
                    action: function (dialog) {
                        dialog.close();
                    }
                }, {
                    label: lang.popups.settingsAlerts.buttonSave,
                    cssClass: 'btn-success btn-outline',
                    action: function (dialog) {
                        that.model.set(alertPropertyName, $popupTpl.find('textarea').val());
                        that.model.save().then(function (response) {
                            if (!response || !response.success) {
                                toastr.error(lang.settings_error_save);
                            } else {
                                toastr.success(lang.settings_message_success);
                                that.trigger('updated');
                                dialog.close();
                            }
                            if (response.errors) {
                            }
                        }, function () {
                            toastr.error(lang.settings_error_save);
                            that.model.set(that.model.previousAttributes());
                            that.render();
                        });
                    }
                }]
            });
        }
    });

});