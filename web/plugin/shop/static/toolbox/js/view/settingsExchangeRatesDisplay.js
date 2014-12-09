define("plugin/shop/toolbox/js/view/settingsExchangeRatesDisplay", [
    'default/js/lib/backbone',
    'plugin/shop/common/js/collection/settings',
    'default/js/lib/utils',
    'default/js/lib/bootstrap-dialog',
    'default/js/lib/bootstrap-alert',
    /* template */
    'default/js/plugin/hbs!plugin/shop/toolbox/hbs/settingsExchangeRatesDisplay',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/toolbox/nls/translation',
    'default/js/lib/select2/select2',
    'default/js/lib/bootstrap-editable',
    'default/js/lib/bootstrap-switch'
], function (Backbone, CollectionSettings, Utils, BootstrapDialog, BSAlerts, tpl, lang) {

    return Backbone.View.extend({
        className: 'panel panel-info',
        lang: lang,
        template: tpl,
        events: {
            'click .refresh-userlist-currencies': 'refreshUserCurrencyList',
            'click .save-currency-display': 'saveCurrencyDisplay'
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
                emptytext: lang.settings_value_editable_emptytext,
                savenochange: true,
                unsavedclass: ''
            };
            this.collection = new CollectionSettings();
            this.collection.setCustomQueryField('Type', 'EXCHANGERATES');
            this.listenTo(this.collection, 'reset', this.render);
            _.bindAll(this, 'refreshUserCurrencyList', 'saveCurrencyDisplay');
        },
        render: function () {
            this.$el.html(tpl(Utils.getHBSTemplateData(this)));
            this.$('.switcher:visible').bootstrapSwitch(this.options.switchOptions);
            this.$('.editable').editable(this.options.editableOptions)
                .on('save', this.saveCurrencyDisplay);
            return this;
        },
        refreshUserCurrencyList: function () {
            var that = this,
                modelsToRemove = {};
            $.get(APP.getApiLink({
                source: 'shop',
                fn: 'exchangerates',
                type: 'userlist'
            }), function (userCurrencyItems) {
                that.$('.list-group .list-group-item').each(function () {
                    var id = $(this).data('id'),
                        model = that.collection.get(id);
                    if (id) {
                        modelsToRemove[model.get('Property')] = model;
                    }
                });
                that.$('.list-group').empty();
                if (userCurrencyItems) {
                    _(userCurrencyItems).each(function (currencyName) {
                        debugger;
                        var $newAgenctTpl = that.$('.currency-display-template').clone();
                        // set id for any existent models
                        if (modelsToRemove[currencyName]) {
                            $newAgenctTpl.data('id', modelsToRemove[currencyName].id).attr('data-id', modelsToRemove[currencyName].id);
                            $newAgenctTpl.removeClass('is-new');
                            delete modelsToRemove[currencyName];
                        } else {
                            $newAgenctTpl.find('.name').text(currencyName);
                            $newAgenctTpl.find('.switcher').bootstrapSwitch(that.options.switchOptions);
                            $newAgenctTpl.find('.editable').text(currencyName);
                            $newAgenctTpl.find('.editable').editable(that.options.editableOptions.rate)
                                .on('save', that.saveCurrencyDisplay);
                        }
                        $newAgenctTpl.removeClass('hidden currency-display-template');
                        that.$('.list-group').append($newAgenctTpl);
                    });
                }
                // remove previous unused models
                _(modelsToRemove).each(function (model) {
                    if (model && model.destroy) {
                        model.destroy({
                            wait: true
                        });
                    }
                });
            });
        },
        saveCurrencyDisplay: function (event) {
            var that = this;
            this.$('.list-group .list-group-item').each(function () {
                debugger;
                var $item = $(this),
                    id = $item.data('id'),
                    model = that.collection.get(id);
                if (model) {
                    // update
                    model.save({
                        Property: $item.find('.name').text(),
                        Label: $item.find('.editable').text(),
                        Value: $item.find('.switcher').is(':checked') ? 1 : 0
                    }, {
                        patch: true,
                        success: function (model, resp) {
                            BSAlerts.success(lang.settings_message_success);
                        }
                    });
                } else {
                    // create
                    that.collection.create({
                        Property: $item.find('.name').text(),
                        Label: $item.find('.editable').text(),
                        Value: $item.find('.switcher').is(':checked') ? 1 : 0,
                        Type: 'EXCHANGERATES'
                    }, {
                        success: function (model, resp) {
                            if (_.isEmpty(resp.errors)) {
                                BSAlerts.success(lang.settings_message_success);
                                $item.attr('data-id', model.id).data('id', model.id);
                                $item.removeClass('is-new');
                                // that.collection.fetch({
                                //     reset: true
                                // });
                            } else {
                                // _(resp.errors).each(function (v, k) {
                                //     $errorsList.append($('<ul>').text(k));
                                // });
                                // $errorsList.removeClass('hidden');
                            }
                        }
                    });
                }
            });
            // var self = this,
            //     $item = $(event.target).closest('.list-group-item'),
            //     $errorsList = $item.find('.errors');
            // if ($item.length !== 1)
            //     return;
            // $errorsList.empty();
            // this.collection.create({
            //     RateA: parseFloat($item.find('.rate-a').text(), 10),
            //     CurrencyA: $item.find('.currency-a').text(),
            //     RateB: parseFloat($item.find('.rate-b').text(), 10),
            //     CurrencyB: $item.find('.currency-b').text()
            // }, {
            //     success: function (model, resp) {
            //         if (model.id) {
            //             $errorsList.addClass('hidden');
            //             $item.data('id', model.id);
            //             $item.removeClass('is-new');
            //             // show add-new button
            //             self.$('.add-currency').removeClass('hidden');
            //             BSAlerts.success(lang.settings_message_success);
            //             self.collection.fetch({
            //                 reset: true
            //             });
            //         }
            //         if (!_.isEmpty(resp.errors)) {
            //             _(resp.errors).each(function (v, k) {
            //                 $errorsList.append($('<ul>').text(k));
            //             });
            //             $errorsList.removeClass('hidden');
            //         }
            //     }
            // });
        }
    });

});