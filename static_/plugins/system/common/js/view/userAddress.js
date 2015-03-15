define([
    'backbone',
    'handlebars',
    'plugins/system/common/js/model/userAddress',
    'utils',
    'bootstrap-alert',
    'bootstrap-dialog',
    'text!plugins/system/common/hbs/partials/userAddress.hbs',
    /* lang */
    'i18n!plugins/system/site/nls/translation',
    'bootstrap-editable'
], function (Backbone, Handlebars, ModelUserAddress, Utils, BSAlert, BootstrapDialog, tpl, lang) {

    var UserAddress = Backbone.View.extend({
        className: 'user-address-item',
        template: Handlebars.compile(tpl), // check
        lang: lang,
        events: {
            "click .saveAddress": "saveAddress",
            "click .removeAddress": "removeAddress",
        },
        initialize: function (options) {
            // debugger;
            this.model = new ModelUserAddress(options.address || {});
            this.model.set('UserID', options.UserID);
            this.listenTo(this.model, 'sync', this.render);
            _.bindAll(this, 'saveAddress', 'removeAddress');
        },
        render: function () {
            var self = this;
            // debugger;
            this.$el.html(this.template(Utils.getHBSTemplateData(this)));

            if (!this.model.get('isRemoved')) {
                this.$('.editable').editable({
                    mode: 'inline',
                    emptytext: lang.profile_page_addresses_label_emptyValue
                });
            }
            return this;
        },
        saveAddress: _.debounce(function (event) {
            var that = this,
                updatedData = $(event.target).parents('.user-address-item').find('.editable').editable('getValue');
            // this.model.set(updatedData);
            this.model.save(updatedData).then(function () {
                setTimeout(function() {
                    that.$('.alert.alert-success').fadeTo(1500, 0).slideUp(500, function(){
                        $(this).remove();
                    });
                    that.$('.alert.alert-danger').fadeTo(3500, 0).slideUp(500, function(){
                        $(this).remove();
                    });
                }, 1000);
            });
        }, 500),
        removeAddress: function () {
            var that = this;
            BootstrapDialog.confirm("Видалити цю адресу", function (rez) {
                if (rez) {
                    that.model.destroy({
                        success: function (m, response) {
                            that.model.set(response);
                            if (response.success) {
                                BSAlert.success(lang.profile_page_editAddress_destroySuccess);
                            }
                            else if (response.error)
                                BSAlert.danger(lang['profile_page_editAddress_error_' + response.error]);
                        }
                    })


                    // .then(function (response) {
                    //         // debugger
                    //         // console.log(that.model.toJSON());
                    //         that.trigger('destroy:ok');
                    //     // success: function (model, response) {
                    //     //     if (response) {
                    //     // }
                    // });
                }
            });
        }
    });
    return UserAddress;

});
