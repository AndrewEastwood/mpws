define("plugin/shop/toolbox/js/view/settingsDeliveryAgencies", [
    'default/js/lib/backbone',
    'plugin/shop/toolbox/js/collection/listDeliveryAgencies',
    'default/js/lib/utils',
    'default/js/lib/bootstrap-dialog',
    'default/js/lib/bootstrap-alert',
    /* template */
    'default/js/plugin/hbs!plugin/shop/toolbox/hbs/settingsDeliveryAgencies',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/toolbox/nls/translation',
    'default/js/lib/bootstrap-editable',
    'default/js/lib/bootstrap-switch'
], function (Backbone, CollectionOrdersExpired, Utils, BootstrapDialog, BSAlerts, tpl, lang) {

    return Backbone.View.extend({
        className: 'panel panel-default',
        lang: lang,
        template: tpl,
        events: {
            'switchChange.bootstrapSwitch .switcher': 'setAgencyState',
            'click .add-delivery': 'addAgency',
            'click .create-delivery': 'createAgency',
            'click .remove-delivery': 'deleteAgency',
            'save .editable': 'updateAgency'
        },
        initialize: function () {
            this.collection = new CollectionOrdersExpired();
            this.collection.queryParams.limit = 0;
            this.collection.setCustomQueryField('Status', 'REMOVED:!=');
            this.listenTo(this.collection, 'reset', this.render);
            this.options = {};
            this.options.switchOptions = {
                size: 'mini'
            };
            this.options.editableOptions = {
                mode: 'inline',
                name: 'Name',
                savenochange: true,
                unsavedclass: '',
                validate: function (value) {
                    if ($.trim(value) === '') {
                        return 'Введіть назву перевізника';
                    }
                }
            };
            _.bindAll(this, 'updateAgency', 'hideSaveButton', 'showSaveButton');
        },
        render: function () {
            this.$el.html(tpl(Utils.getHBSTemplateData(this)));
            this.$('.switcher:visible').bootstrapSwitch(this.options.switchOptions);
            this.$('.editable:visible').editable(this.options.editableOptions)
                .on('save', this.updateAgency)
                .on('shown', this.hideSaveButton)
                .on('hidden', this.showSaveButton);
            this.delegateEvents();
            return this;
        },
        hideSaveButton: function (event) {
            var $item = $(event.target).closest('.list-group-item');
            $item.find('.create-delivery').addClass('hidden');
        },
        showSaveButton: function (event) {
            var $item = $(event.target).closest('.list-group-item');
            // turn back save butto when current item is new entry
            if (typeof $item.data('id') === 'undefined') {
                $item.find('.create-delivery').removeClass('hidden');
            }
        },
        setAgencyState: function (event, state, skip) {
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
                        BSAlerts.danger('Помилка оновлення параметру');
                        $item.find('.switcher').bootstrapSwitch('state', !state, true);
                    }
                });
            }
        },
        addAgency: function () {
            var $newAgenctTpl = this.$('.agency-template').clone();
            $newAgenctTpl.removeClass('hidden agency-template');
            this.$('.list-group').append($newAgenctTpl);
            $newAgenctTpl.find('.editable').editable(this.options.editableOptions)
                .on('save', this.updateAgency)
                .on('shown', this.hideSaveButton)
                .on('hidden', this.showSaveButton);
            // hide add-new button
            this.$('.add-delivery').addClass('hidden');
        },
        createAgency: function (event) {
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
                    self.$('.add-delivery').removeClass('hidden');
                    self.delegateEvents();
                }
            });
        },
        updateAgency: function (event, editData) {
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
                        BSAlerts.danger('Помилка оновлення параметру');
                        self.collection.fetch({
                            reset: true
                        });
                    }
                });
            }
        },
        deleteAgency: function (event) {
            var self = this,
                $item = $(event.target).closest('.list-group-item'),
                id = $item.data('id'),
                model = this.collection.get(id);

            if (!model) {
                // show add-new button
                this.$('.add-delivery').removeClass('hidden');
                $item.remove();
                return;
            }

            BootstrapDialog.confirm("Видалити цей сервіс?", function (rez) {
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