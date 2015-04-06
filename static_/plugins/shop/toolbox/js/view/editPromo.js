define([
    'backbone',
    'handlebars',
    'plugins/shop/toolbox/js/model/promo',
    'utils',
    'bootstrap-dialog',
    'bootstrap-alert',
    /* template */
    'text!plugins/shop/toolbox/hbs/editPromo.hbs',
    /* lang */
    'i18n!plugins/shop/toolbox/nls/translation',
    'bootstrap-editable',
    'moment'
], function (Backbone, Handlebars, ModelPromo, Utils, BootstrapDialog, BSAlert, tpl, lang) {

    function _getTitle(isNew) {
        if (isNew) {
            return $('<span/>').addClass('fa fa-asterisk').append(' ', lang.popup_promo_title_new);
        } else {
            return $('<span/>').addClass('fa fa-pencil').append(' ', lang.popup_promo_title_edit);
        }
    }

    var PopupPromo = Backbone.View.extend({
        template: Handlebars.compile(tpl), // check
        lang: lang,
        initialize: function () {
            this.model = new ModelPromo();
            this.listenTo(this.model, 'sync', this.render);
        },
        render: function () {
            var that = this;
            var $dialog = new BootstrapDialog({
                closable: false,
                draggable: false,
                title: _getTitle(that.model.id),
                message: $(this.template(Utils.getHBSTemplateData(this))),
                buttons: [{
                    label: lang.popup_origin_button_Close,
                    cssClass: 'btn-default btn-link',
                    action: function (dialog) {
                        Backbone.history.navigate(APP.instances.shop.urls.promo, true);
                    }
                }, {
                    label: lang.popup_origin_button_Save,
                    cssClass: 'btn-success btn-outline',
                    action: function (dialog) {
                        // debugger;
                        that.model.save({
                            DateStart: that.$('#datestart').text(),
                            DateExpire: that.$('#dateexpire').text(),
                            Discount: that.$('#discount').val()
                        }, {
                            wait: true,
                            patch: true,
                            success: function (model, response) {
                                // debugger;
                                if (!response || !response.success) {
                                    that.render();
                                } else {
                                    Backbone.history.navigate(APP.instances.shop.urls.promo, true);
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

            this.$('.date').editable({
                format: 'YYYY-MM-DD',
                viewformat: 'YYYY-MM-DD',
                template: 'D / MMMM / YYYY',
                container: 'body',
                mode: 'inline',
                emptytext: '0000-00-00',
                combodate: {
                    minYear: 2014,
                    minuteStep: 1
                }
            });
            return this;
        }
    });

    return PopupPromo;

});