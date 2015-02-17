define("plugin/shop/toolbox/js/view/settingsExchangeRatesDisplay", [
    'default/js/lib/backbone',
    'plugin/shop/common/js/collection/settings',
    'plugin/shop/common/js/model/setting',
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
], function (Backbone, CollectionSettings, ModelSetting, Utils, BootstrapDialog, BSAlerts, tpl, lang) {

    return Backbone.View.extend({
        className: 'panel panel-info',
        lang: lang,
        template: tpl,
        events: {
            'click .refresh-userlist-currencies': 'refreshUserCurrencyList',
            'click .save-currency-display': 'saveCurrencyDisplay',
            'change .currency-list-db-default': 'saveDBCurrencyType',
            'change .currency-list-site-default': 'saveSiteCurrencyType',
            'switchChange.bootstrapSwitch .switcher-show-currency-switcher': 'setShowSiteCurrencySwitcher'
        },
        initialize: function () {
            this.currencyList = null;
            this.dbCurrencyType = null;
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
            this.modelDBPriceCurrency = new ModelSetting({
                name: 'DBPriceCurrencyType'
            });
            this.modelSiteDefaultCurrency = new ModelSetting({
                name: 'SiteDefaultPriceCurrencyType'
            });
            this.modelShowSiteCurrencySwitcher = new ModelSetting({
                name: 'ShowSiteCurrencySelector'
            });
            this.collection = new CollectionSettings('EXCHANGERATES');
            // this.collection.setCustomQueryField('Status', 'REMOVED:!=');
            this.listenTo(this.modelDBPriceCurrency, 'change', this.renderDBCurrencyType);
            this.listenTo(this.modelSiteDefaultCurrency, 'change', this.renderSiteDefaultCurrency);
            this.listenTo(this.modelShowSiteCurrencySwitcher, 'change', this.renderShowSiteCurrencySwitcher);
            this.listenTo(this.collection, 'reset', this.render);
            this.dfdRenderComplete = new $.Deferred();
            _.bindAll(this, 'refreshUserCurrencyList', 'saveCurrencyDisplay', 'saveDBCurrencyType',
                'renderDBCurrencyType', 'renderSiteDefaultCurrency', 'renderShowSiteCurrencySwitcher');
        },
        render: function () {
            var that = this,
                tplData = Utils.getHBSTemplateData(this);
            tplData.extras.modelDBPriceCurrency = this.modelDBPriceCurrency.toJSON();
            tplData.extras.modelSiteDefaultCurrency = this.modelSiteDefaultCurrency.toJSON();
            tplData.extras.modelShowSiteCurrencySwitcher = this.modelShowSiteCurrencySwitcher.toJSON();
            this.$el.html(tpl(tplData));
            this.$('.switcher:visible').bootstrapSwitch(this.options.switchOptions);
            this.$('.editable:visible').editable(this.options.editableOptions);
            this.initializeCurrencyList(this.$('.currency-list'), function (item, currencyList) {
                that.$('.currency-list-db-default').select2({
                    width: 150,
                    placeholder: 'Виберіть валюту',
                    data: _(currencyList).map(function (item) {
                        return {
                            id: item,
                            text: item
                        }
                    }),
                    initSelection: function (e, callback) {
                        if (that.dbCurrencyType) {
                            callback({
                                id: that.dbCurrencyType,
                                text: that.dbCurrencyType
                            });
                        } else {
                            callback();
                        }
                    }
                });
                that.$('.currency-list-site-default').select2({
                    width: 150,
                    placeholder: 'Виберіть валюту',
                    data: _(currencyList).map(function (item) {
                        return {
                            id: item,
                            text: item
                        }
                    }),
                    initSelection: function (e, callback) {
                        if (that.siteDefaultCurrencyType) {
                            callback({
                                id: that.siteDefaultCurrencyType,
                                text: that.siteDefaultCurrencyType
                            });
                        } else {
                            callback();
                        }
                    }
                });
                if (this.dbCurrencyType) {
                    $item.select2('val', this.dbCurrencyType);
                }
                if (this.siteDefaultCurrencyType) {
                    $item.select2('val', this.siteDefaultCurrencyType);
                }
                that.dfdRenderComplete.resolve();
            });
            return this;
        },
        initializeCurrencyList: function ($item, callback) {
            var that = this;
            if (this.currencyList === null) {
                $.get(APP.getApiLink({
                    source: 'shop',
                    fn: 'exchangerates',
                    type: 'currencylist'
                }), function (data) {
                    that.currencyList = data;
                    that.initializeCurrencyList($item, callback);
                    callback($item, that.currencyList);
                });
            } else {
                callback($item, this.currencyList);
            }
        },
        renderDBCurrencyType: function (e) {
            var that = this;
            this.dfdRenderComplete.done(function () {
                that.dbCurrencyType = that.modelDBPriceCurrency.get('Value');
                if (that.$('.currency-list-db-default').length) {
                    that.$('.currency-list-db-default').select2("val", that.dbCurrencyType);
                }
            });
        },
        renderSiteDefaultCurrency: function () {
            var that = this;
            this.dfdRenderComplete.done(function () {
                that.siteDefaultCurrencyType = that.modelSiteDefaultCurrency.get('Value');
                if (that.$('.currency-list-site-default').length) {
                    that.$('.currency-list-site-default').select2("val", that.siteDefaultCurrencyType);
                }
            });
        },
        renderShowSiteCurrencySwitcher: function () {
            var that = this;
            this.dfdRenderComplete.done(function () {
                that.$('.switcher-show-currency-switcher').bootstrapSwitch('state', that.modelShowSiteCurrencySwitcher.get('_isActive'), true);
            });
        },
        saveSiteCurrencyType: function (e) {
            this.modelSiteDefaultCurrency.save({
                Value: e.val
            }, {
                patch: true,
                silent: true,
                success: function () {
                    BSAlerts.success(lang.settings_message_success);
                },
                error: function () {
                    BSAlerts.danger(lang.settings_error_save);
                }
            });
        },
        setShowSiteCurrencySwitcher: function (event, state, skip) {
            var self = this;
            this.modelShowSiteCurrencySwitcher.save({
                Status: !!state ? 'ACTIVE' : 'DISABLED'
            }, {
                patch: true,
                success: function (model) {
                    BSAlerts.success(lang.settings_message_success);
                    self.$('.switcher-config-self-pickup').bootstrapSwitch('state', model.get('_isActive'), true);
                },
                error: function (model) {
                    BSAlerts.danger(lang.settings_error_save);
                    self.$('.switcher-config-self-pickup').bootstrapSwitch('state', !state, true);
                }
            });
        },
        saveDBCurrencyType: function (e) {
            this.modelDBPriceCurrency.save({
                Value: e.val
            }, {
                patch: true,
                silent: true,
                success: function () {
                    BSAlerts.success(lang.settings_message_success);
                    Backbone.trigger('changed:plugin-shop-currency');
                },
                error: function () {
                    BSAlerts.danger(lang.settings_error_save);
                }
            });
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
                        // debugger;
                        var $newAgenctTpl = that.$('.currency-display-template').clone();
                        // set id for any existent models
                        if (modelsToRemove[currencyName]) {
                            $newAgenctTpl.data('id', modelsToRemove[currencyName].id).attr('data-id', modelsToRemove[currencyName].id);
                            $newAgenctTpl.removeClass('is-new');
                            $newAgenctTpl.find('.name').text(modelsToRemove[currencyName].get('Property'));
                            $newAgenctTpl.find('.editable').text(modelsToRemove[currencyName].get('Label'));
                            $newAgenctTpl.find('.switcher').prop('checked', modelsToRemove[currencyName].get('Value') === "1");
                            delete modelsToRemove[currencyName];
                        } else {
                            $newAgenctTpl.find('.name').text(currencyName);
                            $newAgenctTpl.find('.editable').text(currencyName);
                        }
                        $newAgenctTpl.find('.switcher').bootstrapSwitch(that.options.switchOptions);
                        $newAgenctTpl.find('.editable').editable(that.options.editableOptions.rate);
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
                that.$('.label-no-data').toggleClass('hidden', that.$('.list-group .list-group-item').length);
                that.$('.list-group').toggleClass('hidden', !that.$('.list-group .list-group-item').length);
            });
        },
        saveCurrencyDisplay: function (event) {
            var that = this;
            this.$('.list-group .list-group-item').each(function () {
                // debugger;
                var $item = $(this),
                    id = $item.data('id'),
                    model = that.collection.get(id);
                if (model) {
                    // update
                    model.save({
                        Property: $item.find('.name').text(),
                        Label: $item.find('.editable').text(),
                        Value: $item.find('.switcher').is(':checked') ? 1 : 0,
                        Status: 'ACTIVE'
                    }, {
                        patch: true,
                        success: function (model, resp) {
                            Backbone.trigger('changed:plugin-shop-currency');
                            BSAlerts.success(lang.settings_message_success);
                        }
                    });
                } else {
                    // create
                    that.collection.create({
                        Property: $item.find('.name').text(),
                        Label: $item.find('.editable').text(),
                        Value: $item.find('.switcher').is(':checked') ? 1 : 0,
                        Type: 'EXCHANGERATES',
                        Status: 'ACTIVE'
                    }, {
                        success: function (model, resp) {
                            if (_.isEmpty(resp.errors)) {
                                Backbone.trigger('changed:plugin-shop-currency');
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