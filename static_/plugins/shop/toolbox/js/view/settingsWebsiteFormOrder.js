define([
    'backbone',
    'handlebars',
    'plugins/shop/common/js/model/setting',
    'utils',
    'bootstrap-dialog',
    'bootstrap-alert',
    /* template */
    'text!plugins/shop/toolbox/hbs/settingsWebsiteFormOrder.hbs',
    /* lang */
    'i18n!plugins/shop/toolbox/nls/translation',
    'bootstrap-switch',
    'bootstrap-editable'
], function (Backbone, Handlebars, ModelSetting, Utils, BootstrapDialog, BSAlerts, tpl, lang) {

    return Backbone.View.extend({
        className: "panel panel-default shop-settings-website-form-order",
        template: Handlebars.compile(tpl), // check
        lang: lang,
        events: {
            'switchChange.bootstrapSwitch .switcher': 'setSettingStatus'
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
            this.model.setType('FORMORDER');
            this.listenTo(this.model, 'sync', this.render);
            _.bindAll(this, 'setSettingStatus', 'render');
        },
        render: function () {
            var self = this;
            var tplData = Utils.getHBSTemplateData(this);
            tplData.data = _(tplData.data).omit('ID', 'SucessTextLines', 'ShowOrderTrackingLink', 'errors', 'success');
            this.$el.html(this.template(tplData));
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