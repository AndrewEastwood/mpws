define("plugin/shop/toolbox/js/view/settinsProductView", [
    'default/js/lib/backbone',
    'plugin/shop/common/js/model/setting',
    'default/js/lib/utils',
    'default/js/lib/bootstrap-dialog',
    'default/js/lib/bootstrap-alert',
    /* template */
    'default/js/plugin/hbs!plugin/shop/toolbox/hbs/settinsProductView',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/toolbox/nls/translation',
    'default/js/lib/bootstrap-switch'
], function (Backbone, ModelSetting, Utils, BootstrapDialog, BSAlerts, tpl, lang) {

    return Backbone.View.extend({
        className: "panel panel-green shop-settings-product-view",
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
            this.model.setType('PRODUCT');
            this.listenTo(this.model, 'sync', this.render);
        },
        render: function () {
            var tplData = Utils.getHBSTemplateData(this);
            tplData.data = _(tplData.data).omit('ID', 'errors', 'success');
            this.$el.html(tpl(tplData));
            this.$('.switcher:visible').bootstrapSwitch(this.options.switchOptions);
            return this;
        },
        setSettingState: function (event, state, skip) {

            if (skip === true) {
                return;
            }

            var that = this,
                $item = $(event.target).closest('.list-group-item'),
                propName = $item.data('property');

            this.model.set(propName, !!state);
            this.model.save().then(this.render, function () {
                BSAlerts.danger(lang.settings_error_save);
                that.model.set(that.model.previousAttributes());
                that.render();
            });
        }
    });

});