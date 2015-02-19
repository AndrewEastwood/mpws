define("plugin/shop/toolbox/js/view/settingsAddress", [
    'default/js/lib/backbone',
    'plugin/shop/common/js/collection/settings',
    'plugin/shop/toolbox/js/view/popupSettingsAddress',
    'default/js/lib/utils',
    'default/js/lib/bootstrap-dialog',
    'default/js/lib/bootstrap-alert',
    /* template */
    'default/js/plugin/hbs!plugin/shop/toolbox/hbs/settingsAddress',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/toolbox/nls/translation',
    'default/js/lib/bootstrap-switch'
], function (Backbone, CollectionSettings, PopupSettingsAddress, Utils, BootstrapDialog, BSAlerts, tpl, lang) {

    return Backbone.View.extend({
        className: "panel panel-green shop-settings-addresses",
        template: tpl,
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
                size: 'mini',
                onText: '<i class="fa fa-check fa-fw"></i>',
                offText: '<i class="fa fa-times fa-fw"></i>'
            };
            this.collection = new CollectionSettings();
            this.collection.setType('ADDRESS');
            this.listenTo(this.collection, 'sync', this.render);
        },
        render: function () {
            this.$el.html(tpl(Utils.getHBSTemplateData(this)));
            this.$('.switcher:visible').bootstrapSwitch(this.options.switchOptions);
            return this;
        },
        addAddress: function () {
            var self = this,
                popup = new PopupSettingsAddress();
            popup.render();
            popup.on('close', function () {
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
                popup.on('close', function () {
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
                    // self.collection.each(function (collectionModel) {
                    //     if (collectionModel.getAddressUID() === addressUID) {
                    //         collectionModel.destroy({
                    //             wait: true
                    //         });
                    //     }
                    // });
                    // self.collection.fetch({reset: true});
                }
            });
        },
        setSettingState: function (event, state) {
            // if (skip === true) {
            //     return;
            // }
            var self = this,
                $item = $(event.target).closest('.list-group-item'),
                id = $item.data('id'),
                model = this.collection.get(id);

            if (model) {
                model.set('Status', !!state ? 'ACTIVE' : 'DISABLED');
                model.save().fail(function () {
                    BSAlerts.danger(lang.settings_error_save);
                }).always($.proxy(self.collection.fetch, self.collection)({reset: true}));
                // if (allSuccess) {
                //     $item.find('.switcher').bootstrapSwitch('state', model.get('_isActive'), true);
                // } else {
                //     BSAlerts.danger(lang.settings_error_save);
                //     $item.find('.switcher').bootstrapSwitch('state', !state, true);
                // }
            }
        }
    });

});