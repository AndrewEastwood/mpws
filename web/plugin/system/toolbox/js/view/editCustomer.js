define("plugin/system/toolbox/js/view/editCustomer", [
    'default/js/lib/sandbox',
    'default/js/lib/backbone',
    'plugin/system/toolbox/js/model/customer',
    'default/js/lib/utils',
    'default/js/lib/bootstrap-dialog',
    'default/js/lib/bootstrap-alert',
    /* template */
    'default/js/plugin/hbs!plugin/system/toolbox/hbs/editCustomer',
    'default/js/plugin/hbs!default/hbs/animationFacebook',
    /* lang */
    'default/js/plugin/i18n!plugin/system/toolbox/nls/translation',
    'default/js/widget/imageUpload',
    'default/js/lib/select2/select2',
    'default/js/lib/bootstrap-editable'
], function (Sandbox, Backbone, ModelCustomer, Utils, BootstrapDialog, BSAlert, tpl, tplFBAnim, lang, WgtImageUpload) {

    function _getTitle (isNew) {
        if (isNew) {
            return $('<span/>').addClass('fa fa-asterisk').append(' ', lang.editors.customer.titleForNew);
        } else {
            return $('<span/>').addClass('fa fa-pencil').append(' ', lang.editors.customer.titleForExistent);
        }
    }

    var EditCustomer = Backbone.View.extend({
        template: tpl,
        lang: lang,
        className: 'plugin-system-edit-customer',
        initialize: function () {
            var self = this;
            this.model = new ModelCustomer();
            this.listenTo(this.model, 'change', this.render);
        },
        render: function () {
            // debugger
            var that = this;
            var $dialog = new BootstrapDialog({
                closable: false,
                title: _getTitle(this.model.isNew()),
                message: $(tpl(Utils.getHBSTemplateData(this))),
                buttons: [{
                    label: lang.editors.customer.buttonClose,
                    cssClass: 'btn-default btn-link',
                    action: function (dialog) {
                        // debugger
                        Backbone.history.navigate(APP.instances.system.urls.customerList, true);
                    }
                }, {
                    label: lang.editors.customer.buttonSave,
                    cssClass: 'btn-success btn-outline',
                    action: function (dialog) {
                        that.model.save({
                            HostName: that.$('.js-hostname').val(),
                            HomePage: that.$('.js-homepage').val(),
                            Title: that.$('.js-title').val(),
                            AdminTitle: that.$('.js-admintitle').val(),
                            Logo: that.$('.js-logo').val(),
                            Lang: that.$('.js-lang').val(),
                            Locale: that.$('.js-locale').val(),
                            Protocol: that.$('.js-protocol').val(),
                            Plugins: that.getSelectedPlugins().join(',')
                        }, {
                            wait: true,
                            patch: true,
                            success: function (model, response) {
                                // debugger;
                                if (response && response.success) {
                                    BSAlert.success(lang.editors.customer.messageSuccess);
                                    // window.location.reload();
                                } else {
                                    BSAlert.danger(lang.editors.customer.messageError);
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
            this.$('.js-plugins').html(tplFBAnim());

            // get available plugins and check activated for this customer
            var pluginsUrl = APP.getApiLink({
                source: 'system',
                fn: 'plugins'
            });
            $.get(pluginsUrl, function (data) {
                that.$('.js-plugins').empty();
                var customerPlugins = that.model.get('Plugins');
                _(data).each(function (pName) {
                    if (pName === "system") {
                        return;
                    }
                    var isActivated = _(customerPlugins).indexOf(pName) >= 0;
                    that.$('.js-plugins').append(
                        $('<label>').html(
                            [$('<input>').attr({
                                type: 'checkbox',
                                'class': 'js-plugin-item',
                                name: pName,
                                value: pName,
                                checked: isActivated
                            }).prop('checked', isActivated), $('<span>').text(pName)]
                        )
                    );
                });
            });

            // setup logo upload
            var logoUpload = new WgtImageUpload({
                el: this.$el,
                selector: '.temp-upload-image'
            });
            logoUpload.render();
            // .setupFileUploadItem(this.$(), this);
            return this;
        },
        getSelectedPlugins: function () {
            var plugins = this.$('.js-plugin-item:checked').map(function () {
                return $(this).val();
            });
            return plugins.toArray();
        }
    });

    return EditCustomer;

});