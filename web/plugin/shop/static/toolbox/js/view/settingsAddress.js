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
            this.collection.setCustomQueryField('Type', 'ADDRESS');
            this.collection.setCustomQueryField('Status', 'REMOVED:!=');
            this.listenTo(this.collection, 'reset', this.render);
        },
        render: function () {
            var data = Utils.getHBSTemplateData(this);
            data.extras.shopNames = _(data.data).filter(function (item) {
                return /ShopName$/.test(item.Property);
            });
            this.$el.html(tpl(data));
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
                popup = new PopupSettingsAddress(),
                addressUID = model.getAddressUID();

            if (!addressUID) {
                return;
            }

            popup.collection.setCustomQueryField('Property', 'Address_' + addressUID + '_%:LIKE');
            popup.collection.setCustomQueryField('Type', 'ADDRESS');
            popup.collection.setCustomQueryField('Status', 'REMOVED:!=');
            popup.collection.fetch({
                reset: true
            });
            popup.on('close', function () {
                self.collection.fetch({
                    reset: true
                });
            });
        },
        deleteAddress: function (event) {
            var self = this,
                $item = $(event.target).closest('.list-group-item'),
                id = $item.data('id'),
                model = this.collection.get(id),
                addressUID = model.getAddressUID();

            BootstrapDialog.confirm("Видалити цю адресу?", function (rez) {
                if (rez) {
                    self.collection.each(function (collectionModel) {
                        if (collectionModel.getAddressUID() === addressUID) {
                            collectionModel.destroy({
                                wait: true
                            });
                        }
                    });
                    self.collection.fetch({reset: true});
                }
            });
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