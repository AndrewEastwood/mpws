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
        template: Handlebars.compile(tpl), // check
        lang: lang,
        events: {
            "click .add-address": "addNewAddress",
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
            this.addressListActive = [];
            this.$el.html(this.template(Utils.getHBSTemplateData(this)));

            var addresses = this.model.get('Addresses');

            // debugger;
            // console.log(addresses);
            // TODO: optimize
            _(addresses).each(function(address) {
                if (!address.isRemoved)
                    self.renderAddressItem(address);
            });
            _(addresses).each(function(address) {
                if (address.isRemoved)
                    self.renderAddressItem(address);
            });
            // this.$('.table:not(.removed) .editable').editable({
            //     mode: 'inline',
            //     emptytext: lang.profile_page_addresses_label_emptyValue
            // });

            this.refreshAddNewAddressButton();
            this.delegateEvents();

            return this;
        },
        getActiveAddressesCount: function () {
            return _.chain(this.addressListActive).pluck('model').invoke('toJSON').where({isRemoved: false}).value().length;
        },
        canCreateAddress: function () {
            return this.getActiveAddressesCount() < 3;
        },
        refreshAddNewAddressButton: function () {
            this.$(".add-address").toggleClass('hide', !this.canCreateAddress());
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
            addressView.model.on('sync', function() {
                // debugger
                // addressView.render();
                // self.refreshAddNewAddressButton();
                self.model.fetch();
            });
            addressView.model.on('destory', function() {
                // debugger
                // addressView.render();
                // self.refreshAddNewAddressButton();
                self.model.fetch();
            });
            // addressView.model.on('destroy', function() {
            //     debugger
            //     // addressView.model.fetch({silent: true});
            //     addressView.render();
            //     // self.render();
            //     // addressView.prependTo(this.$('.account-addresses'));
            //     // self.addressListActive = _(self.addressListActive).without(addressView);
            //     // self.refreshAddNewAddressButton();
            // });

            this.addressListActive.push(addressView);

            if (!address || !address.isRemoved)
                this.$('.account-addresses').prepend(addressView.$el);
            else
                this.$('.account-addresses').append(addressView.$el);

            this.refreshAddNewAddressButton();
        },
        addNewAddress: function (address) {
            // debugger
            if (!this.canCreateAddress()) {
                return;
            }
            this.$('.address-buttons').addClass('hide');
            this.renderAddressItem();
            this.$('.add-address').addClass('hide');
        }
    });

    return UserAddresses;

});