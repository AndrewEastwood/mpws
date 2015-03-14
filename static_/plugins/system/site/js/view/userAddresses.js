define([
    'jquery',
    'underscore',
    'backbone',
    'handlebars',
    'utils',
    'plugins/system/common/js/view/userAddress',
    'text!plugins/system/site/hbs/userAddresses.hbs',
    /* lang */
    'i18n!plugins/system/site/nls/translation',
    'bootstrap-editable'
], function ($, _, Backbone, Handlebars, Utils, ViewUserAddress, tpl, lang) {

    var UserAddresses = Backbone.View.extend({
        // tagName: 'li',
        className: 'well user-addresses account-profile-addresses',
        addressListActive: [],
        addressListRemoved: [],
        template: Handlebars.compile(tpl), // check
        lang: lang,
        events: {
            "click #account-address-add-btn-ID": "addNewAddress",
        },
        initialize: function () {
            if (this.model) {
                this.listenTo(this.model, 'change', this.render);
            }
            _.bindAll(this, 'addNewAddress', 'refreshAddNewAddressButton', 'renderAddressItem');
        },
        render: function () {
            var self = this;
            // debugger;
            _(this.addressListActive).invoke('remove');
            _(this.addressListRemoved).invoke('remove');
            this.addressListActive = [];
            this.addressListRemoved = [];
            this.$el.html(this.template(Utils.getHBSTemplateData(this)));

            var addresses = this.model.get('Addresses');

            // debugger;
            // console.log(addresses);
            _(addresses).each(function(address){
                self.renderAddressItem(address);
            });

            // this.$('.table:not(.removed) .editable').editable({
            //     mode: 'inline',
            //     emptytext: lang.profile_page_addresses_label_emptyValue
            // });

            this.refreshAddNewAddressButton();

            return this;
        },
        refreshAddNewAddressButton: function () {
            this.$("#account-address-add-btn-ID").toggleClass('hide', this.addressListActive.length >= 3);
        },
        renderAddressItem: function (address) {
            // debugger;
            var self = this;
            var addressView = new ViewUserAddress({
                UserID: this.model.id,
                address: address
            });
            addressView.render();

            // addressView.model.on('change', function() {
            //     self.model.fetch({silent: true});
            // });
            addressView.model.on('destroy:ok', function() {
                // self.model.fetch({silent: true});
                addressView.remove();
                self.addressListActive = _(self.addressListActive).without(addressView);
                
                self.refreshAddNewAddressButton();
            });

            if (address.isRemoved) {
                this.addressListRemoved.push(addressView);
            } else {
                this.addressListActive.push(addressView);
            }

            this.$('.account-addresses').append(addressView.$el);

            this.refreshAddNewAddressButton();
        },
        addNewAddress: function (address) {
            if (this.addressList.length >= 3)
                return;
            this.renderAddressItem();
        }
    });

    return UserAddresses;

});