define([
    'backbone',
    'handlebars',
    'plugins/system/toolbox/js/model/customer',
    'utils',
    'createPopupTitle',
    'bootstrap-dialog',
    'bootstrap-alert',
    /* template */
    'text!plugins/system/toolbox/hbs/editCustomer.hbs',
    'text!base/hbs/animationFacebook.hbs',
    /* lang */
    'i18n!plugins/system/toolbox/nls/translation',
    'image-upload',
    'select2',
    'bootstrap-editable',
    'bootstrap-switch'
], function (Backbone, Handlebars, ModelCustomer, Utils, createPopupTitle, BootstrapDialog, BSAlert, tpl, animSpinnerFB, lang, WgtImageUpload) {

    function _getTitle (isNew) {
        return createPopupTitle(isNew ? lang.editors.customer.titleForNew : lang.editors.customer.titleForExistent,
            APP.instances.system.urls.customersList);
    }

    var EditCustomer = Backbone.View.extend({
        template: Handlebars.compile(tpl), // check
        lang: lang,
        className: 'bootstrap-dialog type-primary size-normal plugin-system-edit-customer',
        initialize: function () {
            this.options = {};
            this.options.switchOptions = {
                onColor: 'success',
                size: 'mini',
                onText: '<i class="fa fa-check fa-fw"></i>',
                offText: '<i class="fa fa-times fa-fw"></i>'
            };
            this.model = new ModelCustomer();
            this.listenTo(this.model, 'change', this.render);
        },
        render: function () {
            var that = this;
            var $dialog = new BootstrapDialog({
                closable: false,
                title: _getTitle(this.model.isNew()),
                message: $(this.template(Utils.getHBSTemplateData(this))),
                buttons: [{
                    label: lang.editors.customer.buttonClose,
                    cssClass: 'btn-default btn-link',
                    action: function (dialog) {
                        // debugger
                        Backbone.history.navigate(APP.instances.system.urls.customersList, true);
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
                            file1: that.$('.js-file1').val(),
                            Lang: that.$('.js-lang').val(),
                            Locale: that.$('.js-locale').val(),
                            Protocol: that.$('.js-protocol').val(),
                            Plugins: that.getSelectedPlugins().join(',')
                        }, {
                            wait: true,
                            success: function (model, response) {
                                // debugger;
                                if (response && response.success) {
                                    BSAlert.success(lang.editors.customer.messageSuccess);
                                    Backbone.history.navigate(APP.instances.system.urls.customersList, true);
                                    window.location.reload();
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
            this.$('.js-plugins').html(animSpinnerFB);

            // get available plugins and check activated for this customer
            var pluginsUrl = APP.getApiLink('system', 'plugins');
            $.get(pluginsUrl, function (data) {
                that.$('.js-plugins').empty();
                var customerPlugins = that.model.get('Plugins'),
                    $list = $('<div>').addClass('list-group');
                _(data).each(function (pName) {
                    if (pName === "system") {
                        return;
                    }
                    var isActivated = _(customerPlugins).indexOf(pName) >= 0;
                    $list.append(
                        $('<span>').addClass('list-group-item').html([
                            $('<input>').attr({
                                type: 'checkbox',
                                'class': 'switcher js-plugin-item',
                                name: pName,
                                value: pName,
                                'checked': isActivated
                            }).prop('checked', isActivated),
                            $('<span>').addClass('property-label').text(pName)
                        ])
                    );
                });
                that.$('.js-plugins').html($list);
                that.$('.js-plugins .switcher').bootstrapSwitch(that.options.switchOptions);
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