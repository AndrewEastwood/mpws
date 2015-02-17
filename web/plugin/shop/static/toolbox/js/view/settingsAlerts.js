define("plugin/shop/toolbox/js/view/settingsAlerts", [
    'default/js/lib/backbone',
    'plugin/shop/common/js/model/setting',
    'default/js/lib/utils',
    'default/js/lib/bootstrap-dialog',
    'default/js/lib/bootstrap-alert',
    /* template */
    'default/js/plugin/hbs!plugin/shop/toolbox/hbs/settingsAlerts',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/toolbox/nls/translation',
    'default/js/lib/bootstrap-switch'
], function (Backbone, ModelSetting, Utils, BootstrapDialog, BSAlerts, tpl, lang) {

    return Backbone.View.extend({
        className: "panel panel-yellow shop-settings-alerts",
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
            this.model = new ModelSetting({type: 'ALERTS'});
            this.listenTo(this.model, 'change', this.render);
        },
        render: function () {
            var tplData = Utils.getHBSTemplateData(this);
            delete tplData.data.ID;
            this.$el.html(tpl(tplData));
            var alertsEnabled = this.$('.shop-property-AllowAlerts .switcher').is(':checked');
            this.$('.switcher-main').html(this.$('.panel-body .shop-property-AllowAlerts').clone(true));
            this.$('.panel-body .shop-property-AllowAlerts').remove();
            this.$('.switcher:visible').bootstrapSwitch(this.options.switchOptions);
            this.$('.panel-body .list-group-item').toggleClass('disabled', !alertsEnabled);
            this.$('.panel-body .switcher').bootstrapSwitch('disabled', !alertsEnabled);
            return this;
        },
        setSettingState: function (event, state, skip) {
            var self = this;
            if (skip === true) {
                return;
            }
            var $item = $(event.target).closest('.list-group-item'),
                propName = $item.data('id');

            this.model.set(propName, !!state, {silent: true});
            this.model.save(this.model.toJSON(), {
                success: function (model) {
                    $item.find('.switcher').bootstrapSwitch('state', model.get('AllowAlerts'), true);
                    if (model.get('AllowAlerts')) {
                        self.$('.panel-body .list-group-item').toggleClass('disabled', !model.get('AllowAlerts'));
                        self.$('.panel-body .switcher').bootstrapSwitch('disabled', !model.get('AllowAlerts'));
                    }
                },
                error: function (model) {
                    BSAlerts.danger(lang.settings_error_save);
                    $item.find('.switcher').bootstrapSwitch('state', !state, true);
                }
            });
        }
    });

});