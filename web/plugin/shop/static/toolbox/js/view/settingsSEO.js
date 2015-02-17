define("plugin/shop/toolbox/js/view/settingsSEO", [
    'default/js/lib/backbone',
    'plugin/shop/common/js/model/setting',
    'default/js/lib/utils',
    'default/js/lib/bootstrap-dialog',
    'default/js/lib/bootstrap-alert',
    /* template */
    'default/js/plugin/hbs!plugin/shop/toolbox/hbs/settingsSEO',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/toolbox/nls/translation',
    'default/js/lib/bootstrap-switch'
], function (Backbone, ModelSetting, Utils, BootstrapDialog, BSAlerts, tpl, lang) {

    return Backbone.View.extend({
        className: "panel panel-green shop-settings-seo",
        template: tpl,
        lang: lang,
        events: {
            'click .list-group-item': 'editValue'
        },
        initialize: function () {
            this.options = {};
            this.options.editableOptions = {
                mode: 'inline',
                name: 'Value',
                emptytext: lang.settings_value_editable_emptytext,
                savenochange: true,
                unsavedclass: ''
            };
            this.model = new ModelSetting({type: 'SEO'});
            this.listenTo(this.model, 'change', this.render);
        },
        render: function () {
            this.$el.html(tpl(Utils.getHBSTemplateData(this)));
            return this;
        },
        editValue: function (event) {
            var self = this,
                $item = $(event.target).closest('.list-group-item'),
                id = $item.data('id'),
                model = this.collection.get(id);
            if (model) {
                BootstrapDialog.show({
                    cssClass: 'popup-settings-seo',
                    title: $item.find('.property-label').text(),
                    message: $('<textarea>').text(model.get('Value')),
                    buttons: [{
                        label: lang.popup_seo_button_Close,
                        cssClass: 'btn-default btn-link',
                        action: function (dialog) {
                            dialog.close();
                        }
                    }, {
                        label: lang.popup_seo_button_Save,
                        cssClass: 'btn-success btn-outline',
                        action: function (dialog) {
                            model.save({
                                Value: dialog.getMessage().val()
                            }, {
                                wait: true,
                                patch: true,
                                success: function (model) {
                                    BSAlerts.success(lang.settings_message_success);
                                    dialog.close();
                                },
                                error: function (model) {
                                    BSAlerts.danger(lang.settings_error_save);
                                }
                            });
                        }
                    }]
                });
            }
        }
    });

});