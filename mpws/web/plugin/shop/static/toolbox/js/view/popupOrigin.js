define("plugin/shop/toolbox/js/view/popupOrigin", [
    'default/js/lib/sandbox',
    'default/js/lib/backbone',
    'plugin/shop/toolbox/js/model/origin',
    'default/js/lib/utils',
    'default/js/lib/bootstrap-dialog',
    'default/js/lib/bootstrap-alert',
    /* template */
    'default/js/plugin/hbs!plugin/shop/toolbox/hbs/popupOrigin',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/toolbox/nls/translation',
    'default/js/lib/bootstrap-editable'
], function (Sandbox, Backbone, ModelOrigin, Utils, BootstrapDialog, BSAlert, tpl, lang) {

    function _getTitle (isEdit) {
        if (isEdit) {
            return $('<span/>').addClass('fa fa-pencil').append(' ', lang.popup_origin_title_edit);
        } else {
            return $('<span/>').addClass('fa fa-asterisk').append(' ', lang.popup_origin_title_new);
        }
    }

    var OrderItem = Backbone.View.extend({
        template: tpl,
        lang: lang,
        initialize: function () {
            this.model = new ModelOrigin();
            this.listenTo(this.model, 'change', this.render);
            this.$title = $('<span/>');
            this.$dialog = new BootstrapDialog({
                title: this.$title,
                message: this.$el,
                cssClass: 'pluginShopOriginPopup',
                // onhide: function () {
                //     self.dialogIsShown = false;
                // },
                buttons: [{
                    label: lang.popup_origin_button_Close,
                    cssClass: 'btn-warning',
                    action: function (dialog) {
                        dialog.close();
                    }
                }, {
                    label: lang.popup_origin_button_Save,
                    cssClass: 'btn-success',
                    action: function (dialog) {
                        dialog.close();
                    }
                }]
            });
        },
        render: function () {
            var self = this;

            this.$title.html(_getTitle(!!self.model.id));

            this.$el.html(tpl(Utils.getHBSTemplateData(this)));

            if (!this.$dialog.isOpened())
                this.$dialog.open();
        }
    });

    return OrderItem;

});