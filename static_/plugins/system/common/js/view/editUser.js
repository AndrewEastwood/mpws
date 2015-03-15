define([
    'backbone',
    'handlebars',
    'plugins/system/common/js/model/user',
    'plugins/system/common/js/model/userAddress',
    'utils',
    'cachejs',
    'bootstrap-dialog',
    'bootstrap-alert',
    /* template */
    'text!plugins/system/common/hbs/editUser.hbs',
    'text!plugins/system/common/hbs/partials/userAddress.hbs',
    /* lang */
    'i18n!plugins/system/common/nls/translation',
    'plugins/system/common/js/view/userAddress',
    // 'image-upload',
    'bootstrap-switch'
], function (Backbone, Handlebars, ModelUser, ModelAddress, Utils, cachejs, BootstrapDialog, BSAlert, tpl, pUserAddr, lang, ViewUserAddress, WgtImageUpload) {

    function _getTitle (isNew) {
        if (isNew) {
            return $('<span/>').addClass('fa fa-asterisk').append(' ', lang.editors.user.titleForNew);
        } else {
            return $('<span/>').addClass('fa fa-pencil').append(' ', lang.editors.user.titleForExistent);
        }
    }

    Handlebars.registerPartial('userAddrItem', pUserAddr);

    var EditUser = Backbone.View.extend({
        template: Handlebars.compile(tpl), // check
        lang: lang,
        className: 'bootstrap-dialog type-primary size-normal plugin-system-edit-user',
        events: {
            'shown.bs.tab a[data-toggle="tab"]': 'saveActiveTab',
            "click .add-address": "addNewAddress",
            "click .saveAddress": "saveAddress",
            "click .removeAddress": "removeAddress",
            'click #account-password-pwdgen-btn-ID': 'generatePassword',
            'submit .account-profile-password form': 'changePassword'
        },
        addressModels: null,
        initialize: function () {
            this.options = {};
            this.options.switchOptions = {
                size: 'mini',
                onText: '<i class="fa fa-check fa-fw"></i>',
                offText: '<i class="fa fa-times fa-fw"></i>'
            };
            this.addressModels = {};
            this.model = new ModelUser();
            this.listenTo(this.model, 'sync', this.render);
            _.bindAll(this, 'saveActiveTab', 'setActiveTab', 'saveAddress');
        },
        render: function () {
            var that = this;

            var _addr = _.chain(this.model.get('Addresses')).each(function (addrItem) {
                addrItem.lang = lang;
                that.addressModels[addrItem.ID] = new ModelAddress(addrItem);
                that.addressModels[addrItem.ID].set('UserID', that.model.id);
            });
            this.model.extras = {
                addrActive: _addr.where({isRemoved: false}).value(),
                addrRemoved: _addr.where({isRemoved: true}).value(),
                isNew: this.model.isNew()
            };

            // debugger

            var $dialog = new BootstrapDialog({
                closable: false,
                title: _getTitle(this.model.isNew()),
                message: $(this.template(Utils.getHBSTemplateData(this))),
                buttons: [{
                    label: lang.editors.user.buttonClose,
                    cssClass: 'btn-default btn-link',
                    action: function (dialog) {
                        // debugger
                        Backbone.history.navigate(APP.instances.system.urls.usersList, true);
                    }
                }, {
                    label: lang.editors.user.buttonSave,
                    cssClass: 'btn-success btn-outline',
                    action: function (dialog) {
                        that.model.save({
                            'FirstName': that.$('.js-firstname').val(),
                            'LastName': that.$('.js-lastname').val(),
                            'Phome': that.$('.js-phone').val(),
                            'EMail': that.$('.js-email').val(),
                            // 'Password': that.$('.js-password').val(),
                            // 'ConfirmPassword': that.$('.js-passwordverify').val(),
                            'p_CanAdmin': that.$('.js-p_CanAdmin').is(':checked'),
                            'p_CanCreate': that.$('.js-p_CanCreate').is(':checked'),
                            'p_CanEdit': that.$('.js-p_CanEdit').is(':checked'),
                            'p_CanUpload': that.$('.js-p_CanUpload').is(':checked'),
                            'p_CanViewReports': that.$('.js-p_CanViewReports').is(':checked'),
                            'p_CanAddUsers': that.$('.js-p_CanAddUsers').is(':checked'),
                            'p_CanMaintain': that.$('.js-p_CanMaintain').is(':checked')
                        }, {
                            wait: true,
                            success: function (model, response) {
                                // debugger;
                                if (response && response.success) {
                                    BSAlert.success(lang.editors.user.messageSuccess);
                                    // window.location.reload();
                                } else {
                                    BSAlert.danger(lang.editors.user.messageError);
                                }
                            }
                        });
                    }
                }]
            });
            // $dialog.open();
            $dialog.realize();
            $dialog.updateTitle();
            $dialog.updateMessage();
            $dialog.updateClosable();
            // debugger
            // this.$el = $dialog.getModalContent().get(0);
            this.$el.html($dialog.getModalContent());
            this.$('.js-permissions .switcher').bootstrapSwitch(this.options.switchOptions);
            this.setActiveTab();

            // _(this.addressListActive).invoke('remove');
            // this.addressListActive = [];
            // var addresses = this.model.get('Addresses');

            // TODO: optimize
            // _(addresses).each(function(address) {
            //     that.renderAddressItem(address);
            // });

            this.refreshAddNewAddressButton();
            // this.$('.address-buttons').removeClass('hide');

            setTimeout(function() {
                that.$('.alert.alert-success').fadeTo(1000, 0).slideUp(500, function(){
                    $(this).remove();
                });
            }, 1000);
            setTimeout(function() {
                that.$('.alert.alert-danger').fadeTo(5000, 0).slideUp(500, function(){
                    $(this).remove();
                });
            }, 30000);
            // // setup logo upload
            // var logoUpload = new WgtImageUpload({
            //     el: this.$el,
            //     selector: '.temp-upload-image'
            // });
            // logoUpload.render();
            // .setupFileUploadItem(this.$(), this);
            return this;
        },
        saveActiveTab: function () {
            cachejs.set('toolboxUserEditActiveTab', this.$('.nav-tabs li.active').find('a').attr('href'));
        },
        setActiveTab: function () {
            var activeTab = cachejs.get('toolboxUserEditActiveTab') || '#general';
            this.$('.nav-tabs li').find('a[href="' + activeTab + '"]').tab('show');
        },
        getActiveAddressesCount: function () {
            return _.chain(this.addressModels).invoke('toJSON').where({isRemoved: false}).value().length;
        },
        canCreateAddress: function () {
            return this.getActiveAddressesCount() < 3;
        },
        refreshAddNewAddressButton: function () {
            this.$(".add-address").toggleClass('hide', !this.canCreateAddress());
        },
        // renderAddressItem: function (address) {
        //     // debugger;
        //     var self = this,
        //         addressView = new ViewUserAddress({
        //             UserID: this.model.id,
        //             address: address
        //         });
        //     addressView.render();

        //     // console.log(address && address.success_address);

        //     addressView.listenTo(addressView.model, 'sync', function (m, response) {
        //         // if (_.isEmpty(address)) {
        //         // self.model.get('Addresses')[m.id] = m.toJSON();
        //         // }
        //         // self.render();
        //         self.model.fetch();
        //     });
        //     // addressView.listenTo(addressView.model, 'destroy', function () {
        //     //     // debugger
        //     //     console.log('render all');
        //     //     self.render();
        //     //     // self.model.fetch();
        //     // });

        //     this.addressListActive.push(addressView);

        //     if (address && address.isRemoved)
        //         this.$('.account-addresses-removed').prepend(addressView.$el);
        //     else
        //         this.$('.account-addresses').prepend(addressView.$el);
        // },
        addNewAddress: function (address) {
            if (!this.canCreateAddress()) {
                return;
            }
            this.$('.address-buttons, .add-address').addClass('hide');
            this.renderAddressItem();
        },
        changePassword: function () {
            this.model.changePassword(this.$('#Password').val(), this.$('#Verify').val());
            return false;
        },
        generatePassword: function (event) {
            var $btn = $(event.target);

            if ($btn.data('pwd')) {
                this.$('#Password, #Verify').val("").prop('disabled', false);
                $btn.data('pwd', false);
                $btn.text(lang.editors.user.buttonGeneratePassword);
                return;
            }

            var pwd = Utils.generatePwd();// Math.random().toString(36).slice(-8);
            this.$('#Password, #Verify').val(pwd).prop('disabled', true);

            $btn.data('pwd', pwd);
            $btn.text(lang.editors.user.buttonGeneratePasswordCancel);

            // show password
            BootstrapDialog.show({
                type: BootstrapDialog.TYPE_WARNING,
                title: lang.editors.user.popups.password.title,
                message: pwd
            });
        },
        saveAddress: _.debounce(function (event) {
            var addrCnt = $(event.target).parents('.user-address-item'),
                addrId = addrCnt.data('id'),
                updatedData = addrCnt.find('.editable').editable('getValue'),
                addrModel = this.addressModels[addrId];
            // this.model.set(updatedData);
            if (addrModel) {
                addrModel.save(updatedData);
            }
        }, 500),
        removeAddress: function (event) {
            var addrCnt = $(event.target).parents('.user-address-item'),
                addrId = addrCnt.data('id'),
                addrModel = this.addressModels[addrId];
            if (addrModel) {
                BootstrapDialog.confirm("Видалити цю адресу", function (rez) {
                    if (rez) {
                        addrModel.destroy({
                            success: function (m, response) {
                                // console.log('destroy success');
                                addrModel.set(response);
                                if (response.success_address) {
                                    BSAlert.success(lang.editors.user.alerts.successAddrRemove);
                                }
                                else if (response.error)
                                    BSAlert.danger(lang.editors.user.alerts['error' + response.error || '']);
                            }
                        });
                    }
                });
            }
        }
        // getSelectedPlugins: function () {
        //     var plugins = this.$('.js-plugin-item:checked').map(function () {
        //         return $(this).val();
        //     });
        //     return plugins.toArray();
        // }
    });

    return EditUser;

});