define("plugin/shop/toolbox/js/view/settingsExchangeRatesDisplay", [
    'default/js/lib/backbone',
    'plugin/shop/common/js/collection/settings',
    'plugin/shop/common/js/model/setting',
    'default/js/lib/utils',
    'default/js/lib/bootstrap-dialog',
    'default/js/lib/bootstrap-alert',
    /* template */
    'default/js/plugin/hbs!plugin/shop/toolbox/hbs/settingsExchangeRatesDisplay',
    'default/js/plugin/hbs!default/hbs/animationFacebook',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/toolbox/nls/translation',
    'default/js/lib/select2/select2',
    'default/js/lib/bootstrap-editable',
    'default/js/lib/bootstrap-switch'
], function (Backbone, CollectionSettings, ModelSetting, Utils, BootstrapDialog, BSAlerts, tpl, tplFBAnim, lang) {

    return Backbone.View.extend({
        className: 'panel panel-info',
        lang: lang,
        template: tpl,
        events: {
            'click .refresh-userlist-currencies': 'refreshUserCurrencyList',
            'click .save-currency-display': 'saveCurrencyDisplay'
        },
        initialize: function () {
            this.currencyList = null;
            this.DBPriceCurrencyType = null;
            this.options = {};
            this.options.switchOptions = {
                size: 'mini',
                onText: '<i class="fa fa-check fa-fw"></i>',
                offText: '<i class="fa fa-times fa-fw"></i>'
            };
            this.options.editableOptions = {
                mode: 'popup',
                emptytext: '0.00',
                savenochange: true,
                unsavedclass: ''
            };
            this.modelSettingMisc = new ModelSetting();
            this.modelSettingMisc.setType('MISC');
            this.collection = new CollectionSettings();
            this.collection.setType('EXCHANAGERATESDISPLAY');
            this.listenTo(this.collection, 'sync', this.render);
            this.listenTo(this.modelSettingMisc, 'sync', this.renderMiscControls);
            this.dfdRenderComplete = new $.Deferred();
            _.bindAll(this, 'refreshUserCurrencyList', 'saveCurrencyDisplay', 'renderMiscControls');
            this.modelSettingMisc.fetch();
            this.collection.fetch({
                reset: true
            });
        },
        render: function () {
            var that = this,
                tplData = Utils.getHBSTemplateData(this);
            this.$el.html(tpl(tplData));
            this.$('.list-group .js-editable-format').editable(this.options.editableOptions);
            // this.$('.switcher:visible').bootstrapSwitch(this.options.switchOptions);
            // show loading anim for misc settings
            this.$('.js-loading').html(tplFBAnim());
            this.dfdRenderComplete.resolve();
            return this;
        },
        renderMiscControls: function () {
            var that = this;
            this.dfdRenderComplete.done(function () {
                that.initializeCurrencyList().done(function (currencyList) {
                    var dbPriceCurrencyType = that.modelSettingMisc.get('DBPriceCurrencyType'),
                        siteDefaultPriceCurrencyType = that.modelSettingMisc.get('SiteDefaultPriceCurrencyType');
                    that.$('.js-loading').empty();
                    that.$('.list-group-misc').removeClass('hidden');
                    that.$('.switcher:visible').bootstrapSwitch(that.options.switchOptions);
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
                            if (dbPriceCurrencyType) {
                                callback({
                                    id: dbPriceCurrencyType,
                                    text: dbPriceCurrencyType
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
                            if (siteDefaultPriceCurrencyType) {
                                callback({
                                    id: siteDefaultPriceCurrencyType,
                                    text: siteDefaultPriceCurrencyType
                                });
                            } else {
                                callback();
                            }
                        }
                    });
                    if (dbPriceCurrencyType) {
                        that.$('.currency-list-db-default').select2('val', dbPriceCurrencyType);
                    }
                    if (siteDefaultPriceCurrencyType) {
                        that.$('.currency-list-site-default').select2('val', siteDefaultPriceCurrencyType);
                    }
                });
            });
        },
        initializeCurrencyList: function () {
            var that = this,
                dfd = new $.Deferred();
            if (this.currencyList === null) {
                $.get(APP.getApiLink({
                    source: 'shop',
                    fn: 'exchangerates',
                    type: 'currencylist'
                }), function (data) {
                    that.currencyList = data;
                    dfd.resolve(data);
                });
            } else {
                dfd.resolve(this.currencyList);
            }
            return dfd;
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
                            $newAgenctTpl.find('.name').text(modelsToRemove[currencyName].get('CurrencyName'));
                            $newAgenctTpl.find('.js-editable-format').text(modelsToRemove[currencyName].get('Format'));
                            delete modelsToRemove[currencyName];
                        } else {
                            $newAgenctTpl.find('.name').text(currencyName);
                            $newAgenctTpl.find('.js-editable-format').text(currencyName);
                        }
                        $newAgenctTpl.find('.js-editable-format').editable(that.options.editableOptions.rate);
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
            // debugger
            // save misc settings related to currency display
            this.modelSettingMisc.save({
                DBPriceCurrencyType: that.$('.currency-list-db-default').select2('val'),
                SiteDefaultPriceCurrencyType: that.$('.currency-list-site-default').select2('val'),
                ShowSiteCurrencySelector: that.$('.switcher-show-currency-switcher').is(':checked')
            }).then(this.renderMiscControls, function () {
                BSAlerts.danger(lang.settings_error_save);
                that.modelSettingMisc.set(that.modelSettingMisc.previousAttributes());
                that.renderMiscControls();
            });
            // save currency formats
            this.$('.list-group .list-group-item').each(function () {
                // debugger;
                var $item = $(this),
                    id = $item.data('id'),
                    model = that.collection.get(id);
                if (model) {
                    // update
                    model.save({
                        CurrencyName: $item.find('.name').text(),
                        Format: $item.find('.js-editable-format').text()
                    }, {
                        success: function (model, resp) {
                            Backbone.trigger('changed:plugin-shop-currency');
                            BSAlerts.success(lang.settings_message_success);
                        }
                    });
                } else {
                    // create
                    that.collection.create({
                        CurrencyName: $item.find('.name').text(),
                        Format: $item.find('.js-editable-format').text()
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
        }
    });

});