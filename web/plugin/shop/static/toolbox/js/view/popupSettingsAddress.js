define("plugin/shop/toolbox/js/view/popupSettingsAddress", [
    'default/js/lib/sandbox',
    'default/js/lib/backbone',
    'plugin/shop/common/js/collection/settings',
    'default/js/lib/utils',
    'default/js/lib/bootstrap-dialog',
    'default/js/lib/bootstrap-alert',
    /* template */
    'default/js/plugin/hbs!plugin/shop/toolbox/hbs/popupSettingsAddress',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/toolbox/nls/translation',
    'default/js/lib/bootstrap-editable'
], function (Sandbox, Backbone, CollectionSettings, Utils, BootstrapDialog, BSAlert, tpl, lang) {

    function _getTitle (isNew) {
        if (isNew) {
            return $('<span/>').addClass('fa fa-asterisk').append(' ', lang.popup_settingAddress_title_new);
        } else {
            return $('<span/>').addClass('fa fa-pencil').append(' ', lang.popup_settingAddress_title_edit);
        }
    }

    var PopupSettingsAddress = Backbone.View.extend({
        template: tpl,
        lang: lang,
        initialize: function () {
            var self = this;
            this.collection = new CollectionSettings();
            this.listenTo(this.collection, 'reset', this.render);
            this.$title = $('<span/>');
            this.$dialog = new BootstrapDialog({
                title: this.$title,
                message: this.$el,
                cssClass: 'popup-settings-address',
                buttons: [{
                    label: lang.popup_origin_button_Close,
                    cssClass: 'btn-default btn-link',
                    action: function (dialog) {
                        dialog.close();
                    }
                //}//, {
                    //label: lang.popup_origin_button_Save,
                    //cssClass: 'btn-success btn-outline',
                    //action: function (dialog) {
                        // debugger;
                        // self.model.save({
                        //     Property: self.$('#name').val(),
                        //     Value: self.$('#description').val(),
                        //     Type: 'ADDRESS'
                        // }, {
                        //     wait: true,
                        //     patch: true,
                        //     success: function (model, response) {
                        //         // debugger;
                        //         if (!response || !response.success) {
                        //             self.render();
                        //         } else {
                        //             dialog.close();
                        //         }
                        //     }
                        // });
                    //}
                }]
            });
        },
        render: function () {
            // debugger;
            this.$title.html(_getTitle(this.collection.isEmpty()));
            this.$el.html(tpl(Utils.getHBSTemplateData(this)));
            if (!this.$dialog.isOpened()) {
                this.$dialog.open();

(function ($) {
    $(function () {

        var addFormGroup = function (event) {
            event.preventDefault();

            var $formGroup = $(this).closest('.form-group');
            var $multipleFormGroup = $formGroup.closest('.multiple-form-group');
            var $formGroupClone = $formGroup.clone();

            $(this)
                .toggleClass('btn-success btn-add btn-danger btn-remove')
                .html('–');

            $formGroupClone.find('input').val('');
            $formGroupClone.find('.concept').text('Phone');
            $formGroupClone.insertAfter($formGroup);

            var $lastFormGroupLast = $multipleFormGroup.find('.form-group:last');
            if ($multipleFormGroup.data('max') <= countFormGroup($multipleFormGroup)) {
                $lastFormGroupLast.find('.btn-add').attr('disabled', true);
            }
        };

        var removeFormGroup = function (event) {
            event.preventDefault();

            var $formGroup = $(this).closest('.form-group');
            var $multipleFormGroup = $formGroup.closest('.multiple-form-group');

            var $lastFormGroupLast = $multipleFormGroup.find('.form-group:last');
            if ($multipleFormGroup.data('max') >= countFormGroup($multipleFormGroup)) {
                $lastFormGroupLast.find('.btn-add').attr('disabled', false);
            }

            $formGroup.remove();
        };

        var selectFormGroup = function (event) {
            event.preventDefault();

            var $selectGroup = $(this).closest('.input-group-select');
            var param = $(this).attr("href").replace("#","");
            var concept = $(this).text();

            $selectGroup.find('.concept').text(concept);
            $selectGroup.find('.input-group-select-val').val(param);

        }

        var countFormGroup = function ($form) {
            return $form.find('.form-group').length;
        };

        $(document).on('click', '.btn-add', addFormGroup);
        $(document).on('click', '.btn-remove', removeFormGroup);
        $(document).on('click', '.dropdown-menu a', selectFormGroup);

    });
})(jQuery);


                
            }
        }
    });

    return PopupSettingsAddress;

});