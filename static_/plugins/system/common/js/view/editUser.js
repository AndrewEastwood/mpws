define([
    'backbone',
    'handlebars',
    'plugins/system/common/js/model/user',
    // 'plugins/system/common/js/model/userAddress',
    'plugins/system/common/js/view/userAddress',
    'utils',
    'createPopupTitle',
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
    'bootstrap-switch',
    'bootstrap-editable'
], function (Backbone, Handlebars, ModelUser, ViewAddress, Utils, createPopupTitle, cachejs, BootstrapDialog, BSAlert, tpl, pUserAddr, lang, ViewUserAddress, WgtImageUpload) {

    function _getTitle (isNew) {
        return createPopupTitle(isNew ? lang.editors.user.titleForNew : lang.editors.user.titleForExistent,
            APP.instances.system.urls.usersList);
    }

    // Handlebars.registerPartial('userAddrItem', pUserAddr);

    var EditUser = Backbone.View.extend({
        template: Handlebars.compile(tpl), // check
        lang: lang,
        className: 'bootstrap-dialog type-primary size-normal plugin-system-edit-user',
        events: {
            'shown.bs.tab a[data-toggle="tab"]': 'saveActiveTab',
            "click .add-address": "addNewAddress",
            // "click .saveAddress": "saveAddress",
            // "click .removeAddress": "removeAddress",
            'click #account-password-pwdgen-btn-ID': 'generatePassword',
            'submit .account-profile-password form': 'changePassword'
        },
        // addressModels: null,
        initialize: function () {
            this.options = {};
            this.options.switchOptions = {
                onColor: 'success',
                size: 'mini',
                onText: '<i class="fa fa-check fa-fw"></i>',
                offText: '<i class="fa fa-times fa-fw"></i>'
            };
            this.addressViews = {};
            this.model = new ModelUser();
            this.listenTo(this.model, 'sync', this.render);
            _.bindAll(this, 'saveActiveTab', 'setActiveTab', 'showSuccessMessage', 'renderAddressItems', 'moveToDisabled', 'attachListenersToAddressView');
        },
        render: function () {
            var that = this;

            // this.model.extras = {
            //     // addrViews: this.addressViews,
            //     // addrActive: _addr.where({isRemoved: false}).value(),
            //     // addrRemoved: _addr.where({isRemoved: true}).value(),
            //     isNew: this.model.isNew()
            // };

            // debugger
            var _addr = _.chain(this.model.get('Addresses')).each(function (addrItem) {
                // addrItem.lang = lang;
                // that.addressModels[addrItem.ID] = new ModelAddress(addrItem);
                // that.addressModels[addrItem.ID].set('UserID', that.model.id);
                // that.addressViews[addrItem.ID].set('UserID', that.model.id);
                 var view = new ViewAddress({
                    address: addrItem,
                    UserID: that.model.id
                });
                that.addressViews[view.cid] = view;
                that.attachListenersToAddressView(view);
                // that.addressViews[addrItem.ID].set('UserID', that.model.id);
            });

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

                        var pwd = that.$('#Password').val(),
                            pwdv = that.$('#Verify').val(),
                            newData = {
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
                            };

                        if (pwd || pwdv) {
                            newData.Password = pwd;
                            newData.ConfirmPassword = pwdv;
                        }

                        that.model.save(newData, {
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

            this.renderAddressItems();
            // this.$('.address-buttons').removeClass('hide');

            // setTimeout(function() {
            //     that.$('.alert.alert-success').fadeTo(1000, 0).slideUp(500, function(){
            //         $(this).remove();
            //     });
            // }, 1000);
            // setTimeout(function() {
            //     that.$('.alert.alert-danger').fadeTo(5000, 0).slideUp(500, function(){
            //         $(this).remove();
            //     });
            // }, 30000);
            // // setup logo upload
            // var logoUpload = new WgtImageUpload({
            //     el: this.$el,
            //     selector: '.temp-upload-image'
            // });
            // logoUpload.render();
            // .setupFileUploadItem(this.$(), this);

            if (this.model.get('success')) {
                this.showSuccessMessage();
            }

            return this;
        },
        moveToDisabled: function (addrView) {
            addrView.render().$el.prependTo(this.$('.account-addresses-removed'));
        },
        renderAddressItems: function () {
            var that = this;

            if (!_.isEmpty(that.addressViews)) {
                _(that.addressViews).invoke('remove');
            }
            that.addressView = {};


            that.$('.account-addresses-removed, .account-addresses').empty();
            _(this.addressViews).each(function (addrView) {
                if (addrView.isActive()) {
                    that.$('.account-addresses').prepend(addrView.render().$el);
                } else {
                    that.$('.account-addresses-removed').prepend(addrView.render().$el);
                }
            });
            this.refreshAddNewAddressButton();
        },
        saveActiveTab: function () {
            cachejs.set('toolboxUserEditActiveTab', this.$('.nav-tabs li.active').find('a').attr('href'));
        },
        setActiveTab: function () {
            var activeTab = cachejs.get('toolboxUserEditActiveTab') || '#general';
            this.$('.nav-tabs li').find('a[href="' + activeTab + '"]').tab('show');
        },
        showSuccessMessage: function () {
            var that = this;
            this.$('.alert.alert-success')
                .removeClass('hidden');
            _.delay(function () {
                that.$('.alert.alert-success .fa').removeClass('hidden').addClass('bounceIn');
            }, 100);
            _.delay(function () {
                that.$('.alert.alert-success')
                    .fadeTo(2000, 0)
                    .slideUp(500, function () {
                        $(this)
                            .addClass('hidden')
                            .removeAttr('style');
                        that.$('.alert.alert-success .fa').addClass('hidden').removeClass('bounceIn');
                    });
            }, 3000);
        },
        getActiveAddressesCount: function () {
            var statesCount = _.chain(this.addressViews).invoke('isActive').countBy(function (state) {
                return state ? 'active' : 'removed';
            }).value();
            // console.log('getActiveAddressesCount = ' + statesCount.active);
            return statesCount.active || 0;
        },
        canCreateAddress: function () {
            return this.getActiveAddressesCount() < 3;
        },
        toggleAddNewAddressButton: function (hide) {
            this.$(".add-address").toggleClass('hide', hide);
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
        attachListenersToAddressView: function (addrView) {
            var that = this;
            addrView.on('custom:saved', function () {
                that.showSuccessMessage();
                that.refreshAddNewAddressButton();
            });
            addrView.on('custom:disabled', function (cid) {
                that.showSuccessMessage();
                that.moveToDisabled(that.addressViews[cid]);
                that.refreshAddNewAddressButton();
            });
            addrView.on('custom:cancel', function (cid) {
                that.addressViews[cid] = null;
                that.addressViews = _(that.addressViews).omit(cid);
                that.refreshAddNewAddressButton();
                // that.showSuccessMessage();
                // that.moveToDisabled(that.addressViews[addrItem.ID]);
            });
        },
        addNewAddress: function (address) {
            var that = this;
            if (!this.canCreateAddress()) {
                return;
            }
            // this.$('.address-buttons, .add-address').addClass('hide');
            var view = new ViewAddress({
                UserID: that.model.id
            });
            that.addressViews[view.cid] = view;
            that.attachListenersToAddressView(view);
            this.toggleAddNewAddressButton(true);
            this.$('.account-addresses').prepend(view.render().$el);

            // that.addressViews.new.model.on('')

            // this.renderAddressItem();
            // var addrItem = {
            //     isNew: true,
            //     UserID: this.model.id,
            //     lang: lang
            // };
            // var newAddrModel = new ModelAddress(addrItem);
            // addrItem.ID = newAddrModel.cid;
            // this.addressModels[newAddrModel.cid] = newAddrModel;
            // this.$('.account-addresses-new').html();
            // this.$('.account-addresses-new .editable').editable({
            //     mode: 'inline',
            //     emptytext: lang.editors.user.labelEmptyValue
            // });
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
        // saveAddress: _.debounce(function (event) {
        //     debugger
        //     var addrCnt = $(event.target).parents('.user-address-item'),
        //         addrId = addrCnt.data('id'),
        //         updatedData = addrCnt.find('.editable').editable('getValue'),
        //         addrModel = this.addressModels[addrId];
        //     // this.model.set(updatedData);
        //     if (addrModel) {
        //         addrModel.save(updatedData).then(this.showSuccessMessage);
        //     }
        // }, 500),
        // removeAddress: function (event) {
        //     var that = this,
        //         addrCnt = $(event.target).parents('.user-address-item'),
        //         addrId = addrCnt.data('id'),
        //         addrModel = this.addressModels[addrId];
        //     if (addrModel) {
        //         BootstrapDialog.confirm("Видалити цю адресу", function (rez) {
        //             if (rez) {
        //                 addrModel.destroy({
        //                     success: function (m, response) {
        //                         // console.log('destroy success');
        //                         if (response.success_address) {
        //                             addrModel.set(response);
        //                             addrCnt.remove();
        //                             this.addressModels = _(this.addressModels).omit(addrId);
        //                             BSAlert.success(lang.editors.user.alerts.successAddrRemove);
        //                             that.showSuccessMessage();
        //                         }
        //                         else if (response.error)
        //                             BSAlert.danger(lang.editors.user.alerts['error' + response.error || '']);
        //                     }
        //                 });
        //             }
        //         });
        //     }
        // }
        // getSelectedPlugins: function () {
        //     var plugins = this.$('.js-plugin-item:checked').map(function () {
        //         return $(this).val();
        //     });
        //     return plugins.toArray();
        // }
    });

    return EditUser;

});