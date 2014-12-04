define("plugin/shop/toolbox/js/view/settingsExchangeRates", [
    'default/js/lib/backbone',
    'plugin/shop/toolbox/js/collection/listExchangeRates',
    'plugin/shop/common/js/model/setting',
    'default/js/lib/utils',
    'default/js/lib/bootstrap-dialog',
    'default/js/lib/bootstrap-alert',
    /* template */
    'default/js/plugin/hbs!plugin/shop/toolbox/hbs/settingsExchangeRates',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/toolbox/nls/translation',
    'default/js/lib/bootstrap-editable',
    'default/js/lib/bootstrap-switch'
], function (Backbone, CollectionExchangeRates, ModelSetting, Utils, BootstrapDialog, BSAlerts, tpl, lang) {

    return Backbone.View.extend({
        className: 'panel panel-info',
        lang: lang,
        template: tpl,
        events: {
            'switchChange.bootstrapSwitch .switcher': 'setExchangeRateState',
            'switchChange.bootstrapSwitch .switcher-config-self-pickup': 'setSelfPickupMode',
            'click .add-currency': 'addExchangeRate',
            'click .create-currency': 'createExchangeRate',
            'click .remove-currency': 'deleteExchangeRate',
            'save .editable': 'updateExchangeRate'
        },
        initialize: function () {
            this.collection = new CollectionExchangeRates();
            this.collection.queryParams.limit = 0;
            this.collection.setCustomQueryField('Status', 'REMOVED:!=');
            this.modelSelfService = new ModelSetting({
                name: 'DeliveryAllowSelfPickup'
            });
            this.listenTo(this.collection, 'reset', this.render);
            this.listenTo(this.modelSelfService, 'change', this.renderSelfServiceSwitcher);
            this.options = {};
            this.options.switchOptions = {
                size: 'mini',
                onText: '<i class="fa fa-check fa-fw"></i>',
                offText: '<i class="fa fa-times fa-fw"></i>'
            };
            this.options.editableOptions = {
                mode: 'inline',
                name: 'Name',
                savenochange: true,
                unsavedclass: '',
                validate: function (value) {
                    if ($.trim(value) === '') {
                        return lang.settings_validation_emptyDeliveryAgentName;
                    }
                }
            };
            _.bindAll(this, 'updateExchangeRate', 'hideSaveButton', 'showSaveButton');
        },
        render: function () {
            this.$el.html(tpl(Utils.getHBSTemplateData(this)));
            this.$('.switcher:visible').bootstrapSwitch(this.options.switchOptions);
            this.$('.editable:visible').editable(this.options.editableOptions)
                .on('save', this.updateExchangeRate)
                .on('shown', this.hideSaveButton)
                .on('hidden', this.showSaveButton);
            this.delegateEvents();
            return this;
        },
        setSelfPickupMode: function (event, state, skip) {
            var self = this;
            this.modelSelfService.save({
                Status: !!state ? 'ACTIVE' : 'DISABLED'
            }, {
                patch: true,
                success: function (model) {
                    self.$('.switcher-config-self-pickup').bootstrapSwitch('state', model.get('_isActive'), true);
                },
                error: function (model) {
                    BSAlerts.danger(lang.settings_error_save);
                    self.$('.switcher-config-self-pickup').bootstrapSwitch('state', !state, true);
                }
            });
        },
        renderSelfServiceSwitcher: function () {
            this.$('.switcher-config-self-pickup').bootstrapSwitch('state', this.modelSelfService.get('_isActive'), true);
        },
        hideSaveButton: function (event) {
            var $item = $(event.target).closest('.list-group-item');
            $item.find('.create-currency').addClass('hidden');
        },
        showSaveButton: function (event) {
            var $item = $(event.target).closest('.list-group-item');
            // turn back save butto when current item is new entry
            if (typeof $item.data('id') === 'undefined') {
                $item.find('.create-currency').removeClass('hidden');
            }
        },
        setExchangeRateState: function (event, state, skip) {
            if (skip === true) {
                return;
            }
            var $item = $(event.target).closest('.list-group-item'),
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
                        BSAlerts.danger(lang.settings_error_save);
                        $item.find('.switcher').bootstrapSwitch('state', !state, true);
                    }
                });
            }
        },
        addExchangeRate: function () {
            var $newAgenctTpl = this.$('.currency-template').clone();
            $newAgenctTpl.removeClass('hidden currency-template');
            this.$('.list-group').append($newAgenctTpl);
            $newAgenctTpl.find('.editable').editable(this.options.editableOptions)
                .on('save', this.updateExchangeRate)
                .on('shown', this.hideSaveButton)
                .on('hidden', this.showSaveButton);
            // hide add-new button
            this.$('.add-currency').addClass('hidden');
        },
        createExchangeRate: function (event) {
            var self = this,
                $item = $(event.target).closest('.list-group-item');
            if ($item.length !== 1)
                return;
            this.collection.create({
                Name: $item.find('.name').text()
            }, {
                success: function (model) {
                    $item.data('id', model.id);
                    $item.find('.switcher').attr('checked', model.get('_isActive')).prop('checked', model.get('_isActive'));
                    $item.find('.switcher').bootstrapSwitch(self.options.switchOptions);
                    $item.removeClass('is-new');
                    // show add-new button
                    self.$('.add-currency').removeClass('hidden');
                    self.delegateEvents();
                }
            });
        },
        updateExchangeRate: function (event, editData) {
            var self = this,
                $item = $(arguments[0].target).closest('.list-group-item'),
                id = $item.data('id'),
                model = this.collection.get(id);

            if (model && editData && editData.newValue) {
                model.save({
                    Name: editData.newValue
                }, {
                    patch: true,
                    error: function (model) {
                        BSAlerts.danger(lang.settings_error_save);
                        self.collection.fetch({
                            reset: true
                        });
                    }
                });
            }
        },
        deleteExchangeRate: function (event) {
            var self = this,
                $item = $(event.target).closest('.list-group-item'),
                id = $item.data('id'),
                model = this.collection.get(id);

            if (!model) {
                // show add-new button
                this.$('.add-currency').removeClass('hidden');
                $item.remove();
                return;
            }

            BootstrapDialog.confirm(lang.settings_msg_confirmation_delete_delivery, function (rez) {
                if (rez) {
                    model.destroy({
                        success: function () {
                            $item.remove();
                        }
                    });
                }
            });
        }
    });

});