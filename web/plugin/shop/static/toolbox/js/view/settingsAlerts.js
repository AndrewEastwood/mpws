define("plugin/shop/toolbox/js/view/settingsAlerts", [
    'default/js/lib/backbone',
    'plugin/shop/common/js/collection/settings',
    'default/js/lib/utils',
    'default/js/lib/bootstrap-dialog',
    'default/js/lib/bootstrap-alert',
    /* template */
    'default/js/plugin/hbs!plugin/shop/toolbox/hbs/settingsAlerts',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/toolbox/nls/translation',
    'default/js/lib/bootstrap-switch'
], function (Backbone, CollectionSettings, Utils, BootstrapDialog, BSAlerts, tpl, lang) {

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
            this.collection = new CollectionSettings();
            this.collection.setCustomQueryField('Type', 'ALERTS');
            this.listenTo(this.collection, 'reset', this.render);
        },
        render: function () {
            this.$el.html(tpl(Utils.getHBSTemplateData(this)));
            var alertsEnabled = this.$('.shop-property-AllowAlerts .switcher').is(':checked');
            this.$('.switcher-main').html(this.$('.panel-body .shop-property-AllowAlerts').clone(true));
            this.$('.panel-body .shop-property-AllowAlerts').remove();
            this.$('.switcher:visible').bootstrapSwitch(this.options.switchOptions);
            this.$('.panel-body .list-group-item').toggleClass('disabled', !alertsEnabled);
            this.$('.panel-body .switcher').bootstrapSwitch('disabled', !alertsEnabled);
            return this;
        },
        setSettingState: function (event, state, skip) {
            if (skip === true) {
                return;
            }
            var self = this,
                $item = $(event.target).closest('.list-group-item'),
                id = $item.data('id'),
                model = this.collection.get(id);

            if (model) {
                model.save({
                    Status: !!state ? 'ACTIVE' : 'DISABLED'
                }, {
                    patch: true,
                    success: function (model) {
                        $item.find('.switcher').bootstrapSwitch('state', model.get('_isActive'), true);
                        if (model.get('Property') === 'AllowAlerts') {
                            self.$('.panel-body .list-group-item').toggleClass('disabled', !model.get('_isActive'));
                            self.$('.panel-body .switcher').bootstrapSwitch('disabled', !model.get('_isActive'));
                        }
                    },
                    error: function (model) {
                        BSAlerts.danger('Помилка оновлення параметру');
                        $item.find('.switcher').bootstrapSwitch('state', !state, true);
                    }
                });
            }
        }
    });

});