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
    'default/js/lib/select2/select2',
    'default/js/lib/bootstrap-editable',
    'default/js/lib/bootstrap-switch'
], function (Backbone, CollectionExchangeRates, ModelSetting, Utils, BootstrapDialog, BSAlerts, tpl, lang) {

    return Backbone.View.extend({
        className: 'panel panel-info',
        lang: lang,
        template: tpl,
        events: {
            'click .add-currency': 'addExchangeRate',
            'click .create-currency': 'createExchangeRate',
            'click .remove-currency': 'deleteExchangeRate'
        },
        initialize: function () {
            this.collection = new CollectionExchangeRates();
            this.collection.queryParams.limit = 0;
            this.collection.setCustomQueryField('Status', 'REMOVED:!=');
            this.listenTo(this.collection, 'reset', this.render);
            _.bindAll(this, 'updateExchangeRate', 'hideSaveButton', 'showSaveButton');
        },
        initCurrencySelector: function ($input) {
            $input.select2({
                placeholder: 'Виберіть валюту',
                ajax: {
                    url: APP.getApiLink({
                        source: 'shop',
                        fn: 'exchangerates',
                        type: 'currencylist'
                    }),
                    results: function (data) {
                        var _results = _(data).map(function (item) {
                            return {
                                id: item,
                                text: item
                            };
                        });
                        // $(".select2-ajax").select2('data', _results);
                        return {
                            results: _results
                        };
                    }
                },
                // initSelection: function (element, callback) {
                //     if (_initOrigin.ID >= 0) {
                //         callback({
                //             id: _initOrigin.ID,
                //             text: _initOrigin.Text
                //         });
                //     }
                // }
            });
        },
        render: function () {
            this.$el.html(tpl(Utils.getHBSTemplateData(this)));
            this.delegateEvents();
            this.$('.currency-list')
            return this;
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
        addExchangeRate: function () {
            var $newAgenctTpl = this.$('.currency-template').clone();
            $newAgenctTpl.removeClass('hidden currency-template');
            this.$('.list-group').append($newAgenctTpl);
            this.initCurrencySelector(this.$('.currency-list'));
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