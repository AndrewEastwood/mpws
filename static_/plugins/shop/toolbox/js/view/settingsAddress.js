define([
    'backbone',
    'handlebars',
    'plugins/shop/common/js/collection/settings',
    'plugins/shop/toolbox/js/view/popupSettingsAddress',
    'utils',
    'bootstrap-dialog',
    'bootstrap-alert',
    /* template */
    'text!plugins/shop/toolbox/hbs/settingsAddress.hbs',
    /* lang */
    'i18n!plugins/shop/toolbox/nls/translation',
    'bootstrap-switch'
], function (Backbone, Handlebars, CollectionSettings, PopupSettingsAddress, Utils, BootstrapDialog, BSAlerts, tpl, lang) {

    return Backbone.View.extend({
        className: "panel panel-default shop-settings-addresses",
        template: Handlebars.compile(tpl), // check
        lang: lang,
        events: {
            'click .add-address': 'addAddress',
            'click .edit-address': 'editAddress',
            'click .remove-address': 'deleteAddress',
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
            this.collection = new CollectionSettings();
            this.collection.setType('ADDRESS');
            this.listenTo(this.collection, 'sync', this.render);
        },
        render: function () {
            this.$el.html(this.template(Utils.getHBSTemplateData(this)));
            this.$('.switcher:visible').bootstrapSwitch(this.options.switchOptions);
            return this;
        },
        addAddress: function () {
            var self = this,
                popup = new PopupSettingsAddress();
            popup.render();
            popup.on('updated', function () {
                self.collection.fetch({
                    reset: true
                });
            });
        },
        editAddress: function () {
            var self = this,
                $item = $(event.target).closest('.list-group-item'),
                id = $item.data('id'),
                model = this.collection.get(id),
                popup = null;

            if (model) {
                // debugger
                popup = new PopupSettingsAddress({model: model});
                popup.render();
                popup.on('updated', function () {
                    self.collection.fetch({
                        reset: true
                    });
                });
            }
        },
        deleteAddress: function (event) {
            var self = this,
                $item = $(event.target).closest('.list-group-item'),
                id = $item.data('id'),
                model = this.collection.get(id);

            BootstrapDialog.confirm(lang.settings_msg_confirmation_delete_address, function (rez) {
                if (rez) {
                    model.destroy().fail(function () {
                        BSAlerts.danger(lang.settings_error_save);
                    }).always($.proxy(self.collection.fetch, self.collection)({reset: true}));
                }
            });
        },
        setSettingState: function (event, state) {
            var self = this,
                $item = $(event.target).closest('.list-group-item'),
                id = $item.data('id'),
                model = this.collection.get(id);

            if (model) {
                model.set('Status', !!state ? 'ACTIVE' : 'DISABLED');
                model.save().fail(function () {
                    BSAlerts.danger(lang.settings_error_save);
                }).always($.proxy(self.collection.fetch, self.collection)({reset: true}));
            }
        }
    });

});