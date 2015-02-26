define([
    'backbone',
    'plugins/shop/common/js/model/setting',
    'utils',
    'base/js/lib/bootstrap-dialog',
    'bootstrap-alert',
    /* template */
    'hbs!plugins/shop/toolbox/hbs/settingsWebsiteFormOrder',
    /* lang */
    'i18n!plugins/shop/toolbox/nls/translation',
    'base/js/lib/bootstrap-switch',
    'base/js/lib/bootstrap-editable'
], function (Backbone, ModelSetting, Utils, BootstrapDialog, BSAlerts, tpl, lang) {

    return Backbone.View.extend({
        className: "panel panel-default shop-settings-website-form-order",
        template: tpl,
        lang: lang,
        events: {
            'switchChange.bootstrapSwitch .switcher': 'setSettingStatus'
        },
        initialize: function () {
            this.options = {};
            this.options.switchOptions = {
                size: 'mini',
                onText: '<i class="fa fa-check fa-fw"></i>',
                offText: '<i class="fa fa-times fa-fw"></i>'
            };
            this.model = new ModelSetting();
            this.model.setType('FORMORDER');
            this.listenTo(this.model, 'sync', this.render);
            _.bindAll(this, 'setSettingStatus', 'render');
        },
        render: function () {
            var self = this;
            var tplData = Utils.getHBSTemplateData(this);
            tplData.data = _(tplData.data).omit('ID', 'SucessTextLines', 'ShowOrderTrackingLink', 'errors', 'success');
            this.$el.html(tpl(tplData));
            this.$('.switcher:visible').bootstrapSwitch(this.options.switchOptions);
            return this;
        },
        setSettingStatus: function (event, status) {
            var that = this,
                $item = $(event.target).closest('.list-group-item'),
                propName = $item.data('property');
            this.model.set(propName, !!status);
            this.model.save().then(this.render, function () {
                BSAlerts.danger(lang.settings_error_save);
                that.model.set(that.model.previousAttributes());
                that.render();
            });
        }
    });

});