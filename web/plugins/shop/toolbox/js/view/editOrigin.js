define([
    'sandbox',
    'backbone',
    'plugins/shop/toolbox/js/model/origin',
    'utils',
    'bootstrap-dialog',
    'bootstrap-alert',
    /* template */
    'hbs!plugins/shop/toolbox/hbs/editOrigin',
    /* lang */
    'i18n!plugins/shop/toolbox/nls/translation',
    'bootstrap-editable'
], function (Sandbox, Backbone, ModelOrigin, Utils, BootstrapDialog, BSAlert, tpl, lang) {

    function _getTitle (isNew) {
        if (isNew) {
            return $('<span/>').addClass('fa fa-asterisk').append(' ', lang.popup_origin_title_new);
        } else {
            return $('<span/>').addClass('fa fa-pencil').append(' ', lang.popup_origin_title_edit);
        }
    }

    var EditOrigin = Backbone.View.extend({
        template: tpl,
        lang: lang,
        className: 'bootstrap-dialog type-primary size-normal plugin-shop-category',
        initialize: function () {
            this.model = new ModelOrigin();
            this.listenTo(this.model, 'sync', this.render);
        },
        render: function () {
            var that = this;
            var $dialog = new BootstrapDialog({
                closable: false,
                draggable: false,
                title: _getTitle(this.model.isNew()),
                message: $(tpl(Utils.getHBSTemplateData(this))),
                buttons: [{
                    label: lang.popup_origin_button_Close,
                    cssClass: 'btn-default btn-link',
                    action: function (dialog) {
                        Backbone.history.navigate(APP.instances.shop.urls.contentList, true);
                    }
                }, {
                    label: lang.popup_origin_button_Save,
                    cssClass: 'btn-success btn-outline',
                    action: function (dialog) {
                        // debugger;
                        that.model.save({
                            Name: that.$('#name').val(),
                            Description: that.$('#description').val(),
                            HomePage: that.$('#homepage').val()
                        }, {
                            wait: true,
                            patch: true,
                            success: function (model, response) {
                                // debugger;
                                if (!response || !response.success) {
                                    that.render();
                                } else {
                                    Backbone.history.navigate(APP.instances.shop.urls.contentList, true);
                                }
                            }
                        });
                    }
                }]
            });

            $dialog.realize();
            $dialog.updateTitle();
            $dialog.updateMessage();
            $dialog.updateClosable();

            this.$el.html($dialog.getModalContent());
            return this;
        }
    });

    return EditOrigin;

});