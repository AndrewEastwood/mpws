define([
    'backbone',
    'plugins/shop/common/js/model/setting',
    'utils',
    'bootstrap-dialog',
    'bootstrap-alert',
    /* template */
    'hbs!plugins/shop/toolbox/hbs/settingsSEO',
    /* lang */
    'i18n!plugins/shop/toolbox/nls/translation',
    'bootstrap-switch'
], function (Backbone, ModelSetting, Utils, BootstrapDialog, BSAlerts, tpl, lang) {

    return Backbone.View.extend({
        className: "panel panel-default shop-settings-seo",
        template: tpl,
        lang: lang,
        events: {
            'click a.list-group-item': 'editValue'
        },
        initialize: function () {
            this.options = {};
            // this.options.editableOptions = {
            //     mode: 'inline',
            //     name: 'Value',
            //     emptytext: lang.settings_value_editable_emptytext,
            //     savenochange: true,
            //     unsavedclass: ''
            // };
            this.model = new ModelSetting();
            this.model.setType('SEO');
            this.listenTo(this.model, 'sync', this.render);
        },
        render: function () {
            var tplData = Utils.getHBSTemplateData(this);
            tplData.data = _(tplData.data).omit('ID', 'errors', 'success');
            this.$el.html(tpl(tplData));
            return this;
        },
        editValue: function (event) {
            var that = this,
                $item = $(event.target).closest('.list-group-item'),
                property = $item.data('property'),
                isProduct = /^product/.test(property.toLowerCase()),
                isCategory = /^category/.test(property.toLowerCase()),
                isHome = /^home/.test(property.toLowerCase()),
                $varInfoDiv = $('<ul>').html(this.$('.js-' + (isProduct ? 'product' : (isCategory ? 'cat' : 'home')) + '-vars').clone());

            BootstrapDialog.show({
                cssClass: 'popup-settings-seo',
                title: $item.find('.property-label').text(),
                message: $('<div>').append([$varInfoDiv, $('<hr>'), $('<textarea>').text(that.model.get(property))]),
                onshow: function (dialog) {
                    var $txtArea = dialog.getMessage().find('textarea');
                    dialog.getMessage().find('ul .label').addClass('label-success').on('click', function () {
                        $txtArea.val($txtArea.val() + ' [' + $(this).text() + ']');
                    });
                },
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
                        that.model.set(property, dialog.getMessage().find('textarea').val());
                        that.model.save().then($.proxy(dialog.close, dialog), function () {
                            BSAlerts.danger(lang.settings_error_save);
                        });
                    }
                }]
            });
        }
    });

});