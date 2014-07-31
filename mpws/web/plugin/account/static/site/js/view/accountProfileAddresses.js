define("plugin/account/site/js/view/accountProfileAddresses", [
    'default/js/lib/sandbox',
    'cmn_jquery',
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'default/js/lib/utils',
    'plugin/account/common/js/view/accountAddress',
    'default/js/plugin/hbs!plugin/account/site/hbs/accountProfileAddresses',
    /* lang */
    'default/js/plugin/i18n!plugin/account/site/nls/translation',
    'default/js/lib/bootstrap-editable'
], function (Sandbox, $, _, Backbone, Utils, ViewAccountAddress, tpl, lang) {

    var AccountProfileAddresses = Backbone.View.extend({
        // tagName: 'li',
        // className: 'col-sm-9 col-md-9',
        addressList: [],
        template: tpl,
        lang: lang,
        events: {
            "click #account-address-add-btn-ID": "addAddress",
        },
        initialize: function () {
            if (this.model)
                this.listenTo(this.model, 'change', this.render);
            _.bindAll(this, 'addAddress');
        },
        render: function () {
            var self = this;
            // debugger;
            this.$el.html(this.template(Utils.getHBSTemplateData(this)));

            var addresses = this.model.get('Addresses');

            // debugger;
            if (addresses && addresses.length >= 3)
                this.$("#account-address-add-btn-ID").addClass('hide');
            else
                this.$("#account-address-add-btn-ID").removeClass('hide');

            _(addresses).each(function(address){
                self.addAddress(address);
            });

            this.$('.editable').editable({
                mode: 'inline',
                emptytext: lang.profile_page_addresses_label_emptyValue
            });

            return this;
        },
        // initialize: function () {
        //     var self = this;

        //     this.model.clearErrors();
        //     this.model.clearStates();

        //     this.listenTo(this.model, "change", this.render);

        //     Sandbox.eventSubscribe("plugin:account:address:remove", function (data) {
        //         if (data.id)
        //             self.model.removeAddress(data.id);
        //         else
        //             $(data.event.target).parents('table.account-profile-address-entry').remove();
        //         self.$("#account-address-add-btn-ID").removeClass('hide');
        //     });

        //     Sandbox.eventSubscribe("plugin:account:address:save", function (data) {
        //         var _addressBlock = $(data.event.target).parents('table.account-profile-address-entry');
        //         if (data.id)
        //             self.model.updateAddress(data.id, _addressBlock.find('.editable').editable('getValue'));
        //         else
        //             self.model.addAddress(_addressBlock.find('.editable').editable('getValue'));
        //     });

        //     this.on('mview:renderComplete', function () {
        //         var profile = self.model.get('profile');
        //         if (profile && profile.addresses) {
        //             // debugger;
        //             if (profile.addresses.length >= 3)
        //                 self.$("#account-address-add-btn-ID").addClass('hide');
        //             else
        //                 self.$("#account-address-add-btn-ID").removeClass('hide');
        //             _(profile.addresses).each(function(address){
        //                 self.addAddress(address);
        //             });
        //         }
        //         self.$('.editable').editable({
        //             mode: 'inline',
        //             emptytext: lang.profile_page_addresses_label_emptyValue
        //         });
        //     });
        // },
        addAddress: function (address) {
            this.$("#account-address-add-btn-ID").toggleClass('hide', this.addressList.length >= 3);

            if (this.addressList.length >= 3)
                return;

            // debugger;
            var addressView = new ViewAccountAddress({
                AccountID: this.model.id,
                address: address
            });
            addressView.render();

            var self = this;

            addressView.model.on('change', function() {
                self.model.fetch();
            });
            addressView.model.on('destroy', function() {
                self.model.fetch();
                this.addressList = _(self.addressList).without(addressView);
                this.$("#account-address-add-btn-ID").toggleClass('hide', this.addressList.length >= 3);
            });

            this.addressList.push(addressView);

            this.$('.account-addresses').append(addressView.$el);

            this.$("#account-address-add-btn-ID").toggleClass('hide', this.addressList.length >= 3);
        }
    });

    return AccountProfileAddresses;

});