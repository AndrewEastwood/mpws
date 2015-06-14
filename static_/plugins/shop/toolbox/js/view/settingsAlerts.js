define([
    'backbone',
    'handlebars',
    'plugins/shop/common/js/model/setting',
    'utils',
    'bootstrap-dialog',
    'bootstrap-alert',
    /* template */
    'text!plugins/shop/toolbox/hbs/settingsAlerts.hbs',
    /* lang */
    'i18n!plugins/shop/toolbox/nls/translation',
    'bootstrap-switch'
], function (Backbone, Handlebars, ModelSetting, Utils, BootstrapDialog, BSAlerts, tpl, lang) {

    return Backbone.View.extend({
        className: "panel panel-default shop-settings-alerts",
        template: Handlebars.compile(tpl), // check
        lang: lang,
        events: {
            'switchChange.bootstrapSwitch .switcher': 'setSettingState'
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
            this.model.setType('ALERTS');
            this.listenTo(this.model, 'sync', this.render);
            _.bindAll(this, 'toggleAlerts', 'render', 'setEmails');
            Backbone.on('system:emails', this.setEmails);
        },
        render: function () {
            var tplData = Utils.getHBSTemplateData(this);
            tplData.data = _(tplData.data).omit('ID', 'errors', 'success');
            this.$el.html(this.template(tplData));
            this.$('.switcher-main').html(this.$('.panel-body .shop-property-AllowAlerts').clone(true));
            this.$('.panel-body .shop-property-AllowAlerts').remove();
            this.$('.switcher:visible').bootstrapSwitch(this.options.switchOptions);
            this.toggleAlerts();
            Backbone.trigger('system:getEmails');
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
        },
        setEmails: function () {
            debugger
        }
    });

});