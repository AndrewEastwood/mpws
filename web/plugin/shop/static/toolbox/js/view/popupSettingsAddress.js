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

    function _getTitle(isNew) {
        if (isNew) {
            return $('<span/>').addClass('fa fa-asterisk').append(' ', lang.popup_settingAddress_title_new);
        } else {
            return $('<span/>').addClass('fa fa-pencil').append(' ', lang.popup_settingAddress_title_edit);
        }
    }

    var PopupSettingsAddress = Backbone.View.extend({
        template: tpl,
        lang: lang,
        events: {
            'click .add-contact': 'addFormGroup',
            'click .remove-contact': 'removeFormGroup',
            'click .contact-types a': 'selectFormGroup',
            'click .tab-contacts': 'showContactsButton',
            'click .button-label': 'setCustomType',
            'click .tab:not(.tab-contacts)': 'hideContactsButton'
        },
        setCustomType: function (event) {
            if ($(event.target).hasClass('custom-type')) {
                this.$('.custom-type').editable('activate');
            }
        },
        showContactsButton: function () {
            this.$('.add-contact').parent().removeClass('hidden');
        },
        hideContactsButton: function () {
            this.$('.add-contact').parent().addClass('hidden');
        },
        initialize: function () {
            var self = this,
                buttons = [];
            this.collection = new CollectionSettings();
            this.options = {};
            this.options.editableOptions = {
                mode: 'popup',
                name: 'Name',
                savenochange: true,
                unsavedclass: '',
                validate: function (value) {
                    if ($.trim(value) === '') {
                        return 'Введіть назву перевізника';
                    }
                }
            };
            buttons.push({
                label: lang.popup_origin_button_Close,
                cssClass: 'btn-default btn-link',
                action: function (dialog) {
                    dialog.close();
                }
            });
            if (this.collection.isEmpty()) {
                buttons.push({
                    label: lang.popup_origin_button_Save,
                    cssClass: 'btn-success btn-outline',
                    action: function (dialog) {
                        debugger;
                        self.collection.create({

                        });
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
                    }
                });
            }
            this.listenTo(this.collection, 'reset', this.render);
            this.$title = $('<span/>');
            this.$dialog = new BootstrapDialog({
                title: this.$title,
                message: this.$el,
                cssClass: 'popup-settings-address',
                buttons: buttons
            });
        },
        render: function () {
            // debugger;
            this.$title.html(_getTitle(this.collection.isEmpty()));
            this.$el.html(tpl(Utils.getHBSTemplateData(this)));
            if (!this.$dialog.isOpened()) {
                this.$dialog.open();
            }
        },
        addFormGroup: function (event) {
            event.preventDefault();
            var $tpl = this.$('.hidden .contact-template').clone();
            $tpl.removeClass('.contact-template');
            this.$('.fields').append($tpl);
        },
        removeFormGroup: function (event) {
            event.preventDefault();
            var $contactItem = $(event.target).closest('.contact-item');
            $contactItem.remove();
        },
        selectFormGroup: function (event) {
            event.preventDefault();

            var $formGroup = $(event.target).closest('.contact-item');
            var type = $(event.target).data("type");
            var concept = $(event.target).text();

            $formGroup.find('.address-type').val(type);
            $formGroup.find('.button-label').toggleClass('custom-type', type === 'custom');
            $formGroup.find('.button-label').text(concept);
            if (type === 'custom') {
                $formGroup.find('.address-label').val(concept);
                $formGroup.find('.button-label').editable(this.options.editableOptions)
                    .on('save', function (e, params) {
                        $(event.target).text(params.newValue);
                        $formGroup.find('.address-label').val(params.newValue);
                    });
            } else {
                $formGroup.find('.button-label').editable('destroy');
            }
        }
    });

    return PopupSettingsAddress;

});