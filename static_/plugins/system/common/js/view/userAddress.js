define([
    'backbone',
    'handlebars',
    'plugins/system/common/js/model/userAddress',
    'utils',
    'bootstrap-alert',
    'bootstrap-dialog',
    'text!plugins/system/common/hbs/partials/userAddress.hbs',
    /* lang */
    'i18n!plugins/system/common/nls/translation',
    'bootstrap-editable'
], function (Backbone, Handlebars, ModelUserAddress, Utils, BSAlert, BootstrapDialog, tpl, lang) {

    var UserAddress = Backbone.View.extend({
        className: 'user-address-item',
        template: Handlebars.compile(tpl), // check
        lang: lang,
        events: {
            "click .saveAddress": "saveAddress",
            "click .removeAddress": "removeAddress",
            "click .cancel": "cancelAdding",
        },
        initialize: function (options) {
            // debugger;
            this.model = new ModelUserAddress(options.address || {});
            this.model.set('UserID', options.UserID);
            this.listenTo(this.model, 'sync', this.render);
            _.bindAll(this, 'render', 'saveAddress', 'removeAddress');
        },
        render: function () {
            // console.log('rendering address item');
            var that = this;
            this.model.extras = {
                isNew: this.model.isNew()
            };
            this.$el.html(this.template(Utils.getHBSTemplateData(this)));
            if (!this.model.get('isRemoved')) {
                this.$('.editable').editable({
                    mode: 'inline',
                    emptytext: lang.editors.user.labelEmptyValue
                });
            }
            this.delegateEvents();
            return this;
        },
        isActive: function () {
            return !this.model.get('isRemoved');
        },
        saveAddress: _.debounce(function (event) {
            // console.log('saving address');
            var that = this,
                updatedData = $(event.target).parents('.user-address-item').find('.editable').editable('getValue');
            this.model.save(updatedData, {wait: true}).then(function (response) {
                if (response.success_address) {
                    that.trigger('custom:saved', that.cid);
                }
                that.render();
            }, this.render);
        }, 500),
        cancelAdding: function () {
            this.remove();
            this.trigger('custom:cancel', this.cid);
        },
        removeAddress: function () {
            var that = this;
            BootstrapDialog.confirm("Видалити цю адресу", function (rez) {
                if (rez) {
                    that.model.destroy({
                        success: function (m, response) {
                            that.model.set(response);
                            if (response.success_address) {
                                that.trigger('custom:disabled', that.cid);
                                BSAlert.success(lang.editors.user.alerts.successAddrRemove);
                            }
                            else if (response.error)
                                BSAlert.danger(lang.editors.user.alerts['error' + response.error || '']);
                        }
                    });
                }
            });
        }
    });
    return UserAddress;

});
