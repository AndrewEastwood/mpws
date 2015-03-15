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
                this.listenTo(this.model, 'sync', this.render);
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

            // TODO: optimize
            _(addresses).each(function(address) {
                self.renderAddressItem(address);
            });

            this.refreshAddNewAddressButton();
            this.$('.address-buttons').removeClass('hide');
            // this.delegateEvents();

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
            var self = this,
                addressView = new ViewUserAddress({
                    UserID: this.model.id,
                    address: address
                });
            addressView.render();

            // console.log(address && address.success_address);

            addressView.listenTo(addressView.model, 'sync', function (m, response) {
                // if (_.isEmpty(address)) {
                // self.model.get('Addresses')[m.id] = m.toJSON();
                // }
                // self.render();
                if (response.success_address) {
                    self.model.fetch();
                }
            });
            // addressView.listenTo(addressView.model, 'destroy', function () {
            //     // debugger
            //     console.log('render all');
            //     self.render();
            //     // self.model.fetch();
            // });

            this.addressListActive.push(addressView);

            if (address && address.isRemoved)
                this.$('.account-addresses-removed').prepend(addressView.$el);
            else
                this.$('.account-addresses').prepend(addressView.$el);
        },
        addNewAddress: function (address) {
            if (!this.canCreateAddress()) {
                return;
            }
            this.$('.address-buttons, .add-address').addClass('hide');
            this.renderAddressItem();
        }
    });

    return UserAddresses;

});