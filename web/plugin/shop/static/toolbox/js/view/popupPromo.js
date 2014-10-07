define("plugin/shop/toolbox/js/view/popupPromo", [
    'default/js/lib/sandbox',
    'default/js/lib/backbone',
    'plugin/shop/toolbox/js/model/promo',
    'default/js/lib/utils',
    'default/js/lib/bootstrap-dialog',
    'default/js/lib/bootstrap-alert',
    /* template */
    'default/js/plugin/hbs!plugin/shop/toolbox/hbs/popupPromo',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/toolbox/nls/translation',
    'default/js/lib/bootstrap-editable',
    'default/js/lib/moment'
], function (Sandbox, Backbone, ModelPromo, Utils, BootstrapDialog, BSAlert, tpl, lang) {

    function _getTitle (isNew) {
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
                viewformat: 'DD.MM.YYYY',
                template: 'D / MMMM / YYYY',
                combodate: {
                    minYear: 2000,
                    maxYear: 9015,
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