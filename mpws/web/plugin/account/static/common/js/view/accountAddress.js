define("plugin/account/common/js/view/accountAddress", [
    'default/js/lib/backbone',
    'plugin/account/common/js/model/accountAddress',
    'default/js/lib/utils',
    'default/js/plugin/hbs!plugin/account/common/hbs/partials/accountAddress',
    /* lang */
    'default/js/plugin/i18n!plugin/account/site/nls/translation',
    'default/js/lib/bootstrap-editable'
], function (Backbone, ModelAccountAddress, Utils, tpl, lang) {

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
            this.model.set('AccountID', options.AccountID);
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
            // debugger;
            // var data = $(event.target).data();
            var _addressBlock = $(event.target).parents('table.account-profile-address-entry');
            var updatedData = _addressBlock.find('.editable').editable('getValue');
            this.model.set(updatedData, {silent: true});
            this.model.save(this.model.toJSON(), {patch: true});
        },
        removeAddress: function (event) {
            // debugger;
            this.model.destroy(this.model.toJSON());
            this.$el.off().remove();
            // var data = $(event.target).data();
            // if (data.id)
            //     this.model.removeAddress(data.id);
            // else
            //     $(event.target).parents('table.account-profile-address-entry').remove();
            // this.$("#account-address-add-btn-ID").removeClass('hide');
        }
    });
    return AccountAddress;

});