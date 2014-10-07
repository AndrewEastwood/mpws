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
            'click .tab:not(.tab-contacts)': 'hideContactsButton'
        },
        showContactsButton: function () {
            this.$('.add-contact').parent().removeClass('hidden');
        },
        hideContactsButton: function () {
            this.$('.add-contact').parent().addClass('hidden');
        },
        getPropertyName: function (Name) {
            if (this.options.isNew) {
                return 'Address_' + this.options.newID + '_' + Name;
            } else {
                var model = self.collection.at(0),
                    property = model && model.get('Property') || "",
                    addressMatch = property.match(/\w+_([0-9]+)_\w+/),
                    addressUID = addressMatch && addressMatch[1];
                if (addressUID) {
                    return 'Address_' + addressUID + '_' + Name;
                } else {
                    throw 'Cannot find address uid in the property: ' + property + ' with collection len ' + self.collection.length;
                }
            }
        },
        initialize: function () {
            var self = this,
                buttons = [];
            this.collection = new CollectionSettings();
            this.options = {};
            this.options.newID = (new Date()).getTime();
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
                        self.$('.tab-content .form-control').each(function () {
                            self.collection.create({
                                Property: self.getPropertyName($(this).data('property')),
                                Label: $(this).data('label') || null,
                                Value: $(this).val(),
                                Type: 'ADDRESS'
                            });
                        });
                        dialog.close();
                        self.trigger('close');
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
            this.options.isNew = this.collection.isEmpty();
            this.$title.html(_getTitle(this.options.isNew));
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

            var $menuItem = $(event.target);
            var $formGroup = $menuItem.closest('.contact-item');
            var $control = $formGroup.find('.form-control');
            var $buttonLabel = $formGroup.find('.button-label');
            var type = $menuItem.data("type");
            var concept = $menuItem.text();
            var isCustom = type.toLowerCase() === 'custom';

            $control.data('property', type).attr('data-property', type);
            $control.data('label', concept).attr('data-label', concept);
            $buttonLabel.text(concept);

            $buttonLabel.toggleClass('custom-type', isCustom);
            if (isCustom) {
                $buttonLabel.editable(this.options.editableOptions)
                    .on('save', function (e, params) {
                        $menuItem.text(params.newValue);
                        $control.data('label', params.newValue).attr('data-label', params.newValue);
                    });
            } else {
                $buttonLabel.editable('destroy');
            }
        }
    });

    return PopupSettingsAddress;

});