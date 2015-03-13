define([
    'backbone',
    'handlebars',
    'plugins/system/common/js/model/user',
    'utils',
    'bootstrap-dialog',
    'bootstrap-alert',
    /* template */
    'text!plugins/system/toolbox/hbs/editUser.hbs',
    /* lang */
    'i18n!plugins/system/toolbox/nls/translation',
    // 'image-upload',
    'bootstrap-switch'
], function (Backbone, Handlebars, ModelUser, Utils, BootstrapDialog, BSAlert, tpl, lang, WgtImageUpload) {

    function _getTitle (isNew) {
        if (isNew) {
            return $('<span/>').addClass('fa fa-asterisk').append(' ', lang.editors.user.titleForNew);
        } else {
            return $('<span/>').addClass('fa fa-pencil').append(' ', lang.editors.user.titleForExistent);
        }
    }

    var EditUser = Backbone.View.extend({
        template: Handlebars.compile(tpl), // check
        lang: lang,
        className: 'bootstrap-dialog type-primary size-normal plugin-system-edit-user',
        initialize: function () {
            this.options = {};
            this.options.switchOptions = {
                size: 'mini',
                onText: '<i class="fa fa-check fa-fw"></i>',
                offText: '<i class="fa fa-times fa-fw"></i>'
            };
            this.model = new ModelUser();
            this.listenTo(this.model, 'change', this.render);
        },
        render: function () {
            var that = this;
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
                            'p_CanView': that.$('.js-p_CanView').is(':checked'),
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

            // // setup logo upload
            // var logoUpload = new WgtImageUpload({
            //     el: this.$el,
            //     selector: '.temp-upload-image'
            // });
            // logoUpload.render();
            // .setupFileUploadItem(this.$(), this);
            return this;
        },
        // getSelectedPlugins: function () {
        //     var plugins = this.$('.js-plugin-item:checked').map(function () {
        //         return $(this).val();
        //     });
        //     return plugins.toArray();
        // }
    });

    return EditUser;

});