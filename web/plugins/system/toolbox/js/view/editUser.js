define([
    'sandbox',
    'backbone',
    'plugins/system/common/js/model/user',
    'utils',
    'bootstrap-dialog',
    'bootstrap-alert',
    /* template */
    'hbs!plugins/system/toolbox/hbs/editUser',
    'hbs!base/hbs/animationFacebook',
    /* lang */
    'i18n!plugins/system/toolbox/nls/translation',
    'image-upload',
    'select2',
    'bootstrap-editable',
    'bootstrap-switch'
], function (Sandbox, Backbone, ModelUser, Utils, BootstrapDialog, BSAlert, tpl, tplFBAnim, lang, WgtImageUpload) {

    function _getTitle (isNew) {
        if (isNew) {
            return $('<span/>').addClass('fa fa-asterisk').append(' ', lang.editors.user.titleForNew);
        } else {
            return $('<span/>').addClass('fa fa-pencil').append(' ', lang.editors.user.titleForExistent);
        }
    }

    var EditUser = Backbone.View.extend({
        template: tpl,
        lang: lang,
        className: 'bootstrap-dialog type-primary size-normal plugin-system-edit-user',
        initialize: function () {
            this.model = new ModelUser();
            this.listenTo(this.model, 'change', this.render);
        },
        render: function () {
            var that = this;
            var $dialog = new BootstrapDialog({
                closable: false,
                title: _getTitle(this.model.isNew()),
                message: $(tpl(Utils.getHBSTemplateData(this))),
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
                            'Password': that.$('.js-password').val(),
                            'ConfirmPassword': that.$('.js-passwordverify').val()
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
            // this.$('.js-plugins').html(tplFBAnim());

            // get available plugins and check activated for this user
            // var pluginsUrl = APP.getApiLink('system', 'plugins');
            // $.get(pluginsUrl, function (data) {
            //     that.$('.js-plugins').empty();
            //     var userPlugins = that.model.get('Plugins');
            //     _(data).each(function (pName) {
            //         if (pName === "system") {
            //             return;
            //         }
            //         var isActivated = _(userPlugins).indexOf(pName) >= 0;
            //         that.$('.js-plugins').append(
            //             $('<label>').html(
            //                 [$('<input>').attr({
            //                     type: 'checkbox',
            //                     'class': 'js-plugin-item',
            //                     name: pName,
            //                     value: pName,
            //                     checked: isActivated
            //                 }).prop('checked', isActivated), $('<span>').text(pName)]
            //             )
            //         );
            //     });
            // });

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