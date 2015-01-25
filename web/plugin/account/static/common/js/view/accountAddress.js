define("plugin/account/common/js/view/accountAddress", [
    'default/js/lib/backbone',
    'plugin/account/common/js/model/accountAddress',
    'default/js/lib/utils',
    'default/js/lib/bootstrap-alert',
    'default/js/plugin/hbs!plugin/account/common/hbs/partials/accountAddress',
    /* lang */
    'default/js/plugin/i18n!plugin/account/site/nls/translation',
    'default/js/lib/bootstrap-editable'
], function (Backbone, ModelAccountAddress, Utils, BSAlert, tpl, lang) {

    var AccountAddress = Backbone.View.extend({
        template: tpl,
        lang: lang,
        events: {
            "click #save-btn": "saveAddress",
            "click .account-address-remove": "removeAddress",
        },
        initialize: function (options) {
            // debugger;
            this.model = new ModelAccountAddress(options.address || {});
            this.model.set('UserID', options.UserID);
            this.listenTo(this.model, 'change', this.render);
            _.bindAll(this, 'saveAddress', 'removeAddress');
        },
        render: function () {
            var self = this;
            // debugger;
            this.$el.html(this.template(Utils.getHBSTemplateData(this)));

            this.$el.find('.editable').editable({
                mode: 'inline',
                emptytext: lang.profile_page_addresses_label_emptyValue
            });
            return this;
        },
        saveAddress: function (event) {
            var _addressBlock = $(event.target).parents('table.account-profile-address-entry');
            var updatedData = _addressBlock.find('.editable').editable('getValue');
            this.model.save(updatedData, {patch: true});
        },
        removeAddress: function (event) {
            this.model.destroy({
                success: function (model, response) {
                    if (response) {
                        if (response.ok) {
                            BSAlert.success(lang.profile_page_editAddress_destroySuccess);
                            model.trigger('destroy:ok');
                        }
                        else if (response.error)
                            BSAlert.danger(lang['profile_page_editAddress_error_' + response.error]);
                    }
                }
            });
        }
    });
    return AccountAddress;

});
