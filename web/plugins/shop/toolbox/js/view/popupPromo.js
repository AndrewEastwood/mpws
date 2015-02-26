define([
    'sandbox',
    'backbone',
    'plugins/shop/toolbox/js/model/promo',
    'utils',
    'base/js/lib/bootstrap-dialog',
    'bootstrap-alert',
    /* template */
    'hbs!plugins/shop/toolbox/hbs/popupPromo',
    /* lang */
    'i18n!plugins/shop/toolbox/nls/translation',
    'base/js/lib/bootstrap-editable',
    'moment'
], function (Sandbox, Backbone, ModelPromo, Utils, BootstrapDialog, BSAlert, tpl, lang) {

    function _getTitle(isNew) {
        if (isNew) {
            return $('<span/>').addClass('fa fa-asterisk').append(' ', lang.popup_promo_title_new);
        } else {
            return $('<span/>').addClass('fa fa-pencil').append(' ', lang.popup_promo_title_edit);
        }
    }

    var PopupPromo = Backbone.View.extend({
        template: tpl,
        lang: lang,
        initialize: function () {
            var self = this;
            this.model = new ModelPromo();
            this.listenTo(this.model, 'change', this.render);
            this.$title = $('<span/>');
            this.$dialog = new BootstrapDialog({
                title: this.$title,
                message: this.$el,
                cssClass: 'popup-promo',
                closable: false,
                buttons: [{
                    label: lang.popup_origin_button_Close,
                    cssClass: 'btn-default btn-link',
                    action: function (dialog) {
                        dialog.close();
                    }
                }, {
                    label: lang.popup_origin_button_Save,
                    cssClass: 'btn-success btn-outline',
                    action: function (dialog) {
                        // debugger;
                        self.model.save({
                            DateStart: self.$('#datestart').text(),
                            DateExpire: self.$('#dateexpire').text(),
                            Discount: self.$('#discount').val()
                        }, {
                            wait: true,
                            patch: true,
                            success: function (model, response) {
                                // debugger;
                                if (!response || !response.success) {
                                    self.render();
                                } else {
                                    dialog.close();
                                }
                            }
                        });
                    }
                }]
            });
        },
        render: function () {
            // debugger;
            this.$title.html(_getTitle(this.model.isNew()));
            this.$el.html(tpl(Utils.getHBSTemplateData(this)));
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
            if (!this.$dialog.isOpened()) {
                this.$dialog.open();
            }
        }
    });

    return PopupPromo;

});