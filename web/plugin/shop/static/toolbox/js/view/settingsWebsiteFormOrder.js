define("plugin/shop/toolbox/js/view/settingsWebsiteFormOrder", [
    'default/js/lib/backbone',
    'plugin/shop/common/js/collection/settings',
    'default/js/lib/utils',
    'default/js/lib/bootstrap-dialog',
    'default/js/lib/bootstrap-alert',
    /* template */
    'default/js/plugin/hbs!plugin/shop/toolbox/hbs/settingsWebsiteFormOrder',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/toolbox/nls/translation',
    'default/js/lib/bootstrap-switch',
    'default/js/lib/bootstrap-editable'
], function (Backbone, CollectionSettings, Utils, BootstrapDialog, BSAlerts, tpl, lang) {

    return Backbone.View.extend({
        className: "panel panel-green shop-settings-website-form-order",
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
            this.options.editableOptions = {
                mode: 'popup',
                name: 'Value',
                emptytext: lang.settings_value_editable_emptytext,
                savenochange: true,
                unsavedclass: ''
            };
            this.collection = new CollectionSettings('FORMORDER');
            this.listenTo(this.collection, 'reset', this.render);
        },
        render: function () {
            var self = this;
            this.$el.html(tpl(Utils.getHBSTemplateData(this)));
            this.$('.switcher:visible').bootstrapSwitch(this.options.switchOptions);
            this.$('.property-value').editable(this.options.editableOptions)
                .on('save', function (e, params) {
                    self.setSettingValue(e, params.newValue, params.oldValue);
                });
            return this;
        },
        setSettingValue: function (event, value, oldValue, skip) {
            if (skip === true) {
                return;
            }
            var self = this,
                $item = $(event.target).closest('.list-group-item'),
                id = $item.data('id'),
                model = this.collection.get(id);

            if (model) {
                model.save({
                    Value: value
                }, {
                    patch: true,
                    success: function (model) {
                        $item.find('.property-value').text(value);
                    },
                    error: function (model) {
                        BSAlerts.danger(lang.settings_error_save);
                        $item.find('.property-value').text(oldValue);
                    }
                });
            }
        },
        setSettingStatus: function (event, status, skip) {
            if (skip === true) {
                return;
            }
            var self = this,
                $item = $(event.target).closest('.list-group-item'),
                id = $item.data('id'),
                model = this.collection.get(id);

            if (model) {
                model.save({
                    Status: !!status ? 'ACTIVE' : 'DISABLED'
                }, {
                    patch: true,
                    success: function (model) {
                        $item.find('.switcher').bootstrapSwitch('state', model.get('_isActive'), true);
                    },
                    error: function (model) {
                        BSAlerts.danger(lang.settings_error_save);
                        $item.find('.switcher').bootstrapSwitch('state', !status, true);
                    }
                });
            }
        }
    });

});