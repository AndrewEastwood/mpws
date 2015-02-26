define([
    'backbone',
    'plugins/shop/common/js/model/setting',
    'utils',
    'base/js/lib/bootstrap-dialog',
    'bootstrap-alert',
    /* template */
    'hbs!plugins/shop/toolbox/hbs/settingsAlerts',
    /* lang */
    'i18n!plugins/shop/toolbox/nls/translation',
    'base/js/lib/bootstrap-switch'
], function (Backbone, ModelSetting, Utils, BootstrapDialog, BSAlerts, tpl, lang) {

    return Backbone.View.extend({
        className: "panel panel-default shop-settings-alerts",
        template: tpl,
        lang: lang,
        events: {
            'switchChange.bootstrapSwitch .switcher': 'setSettingState'
        },
        initialize: function () {
            this.options = {};
            this.options.switchOptions = {
                size: 'mini',
                onText: '<i class="fa fa-check fa-fw"></i>',
                offText: '<i class="fa fa-times fa-fw"></i>'
            };
            this.model = new ModelSetting();
            this.model.setType('ALERTS');
            this.listenTo(this.model, 'sync', this.render);
            _.bindAll(this, 'toggleAlerts', 'render');
        },
        render: function () {
            var tplData = Utils.getHBSTemplateData(this);
            tplData.data = _(tplData.data).omit('ID', 'errors', 'success');
            this.$el.html(tpl(tplData));
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

            // debugger
            this.model.set(propName, !!state);
            this.model.save().then(this.render, function () {
                BSAlerts.danger(lang.settings_error_save);
                that.model.set(that.model.previousAttributes());
                that.render();
            });
        }
    });

});