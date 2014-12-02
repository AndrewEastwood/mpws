define("plugin/shop/toolbox/js/view/settingsSEO", [
    'default/js/lib/backbone',
    'plugin/shop/common/js/collection/settings',
    'default/js/lib/utils',
    'default/js/lib/bootstrap-dialog',
    'default/js/lib/bootstrap-alert',
    /* template */
    'default/js/plugin/hbs!plugin/shop/toolbox/hbs/settingsSEO',
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
            this.collection.setCustomQueryField('Type', 'SEO');
            this.listenTo(this.collection, 'reset', this.render);
        },
        render: function () {
            this.$el.html(tpl(Utils.getHBSTemplateData(this)));
            this.$('.switcher:visible').bootstrapSwitch(this.options.switchOptions);
            this.$('.property-value').editable(this.options.editableOptions)
                .on('save', function (e, params) {
                    self.setSettingValue(e, params.newValue, params.oldValue);
                });
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
                        BSAlerts.danger(lang.settings_error_save);
                        $item.find('.switcher').bootstrapSwitch('state', !state, true);
                    }
                });
            }
        }
    });

});