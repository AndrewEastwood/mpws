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
    'default/js/lib/bootstrap-editable',
    'default/js/components/x-bootstrap-wysihtml5'
], function (Sandbox, Backbone, CollectionSettings, Utils, BootstrapDialog, BSAlert, tpl, lang, x, dfdXEditWysi) {

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
                var model = this.collection.at(0),
                    addressUID = model && model.getAddressUID();
                if (addressUID) {
                    return 'Address_' + addressUID + '_' + Name;
                } else {
                    throw 'Cannot find address uid in the model: ' + model.toJSON() + '; with collection len ' + this.collection.length;
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
                        return 'Введіть значення';
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
                        var modelID = null,
                            model = null,
                            property = null,
                            value = null;
                        self.$('.tab-content .setting').each(function () {
                            modelID = $(this).attr('id');
                            property = $(this).data('property');
                            value = $(this).val() || $(this).text();
                            if (/Shipping|Payment/.test(property)) {
                                value = $(this).editable('getValue', true);
                            }
                            if (modelID) {
                                // update each field
                                model = self.collection.get(modelID);
                                // don't use wrong ids
                                if (!model) {
                                    return;
                                }
                                if ($(this).data('remove') === 1) {
                                    model.destroy();
                                } else {
                                    model.save({
                                        Property: self.getPropertyName(property),
                                        Label: $(this).data('label') || null,
                                        Value: value
                                    }, {
                                        patch: true
                                    });
                                }
                            } else {
                            // create new bunch of fields
                                self.collection.create({
                                    Property: self.getPropertyName(property),
                                    Label: $(this).data('label') || null,
                                    Value: value,
                                    Type: 'ADDRESS'
                                });
                            }
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
            var that = this,
                data = Utils.getHBSTemplateData(this),
                tmpAddressFieldsName = null,
                tmpModelData = null;
            this.options.isNew = this.collection.isEmpty();
            this.$title.html(_getTitle(this.options.isNew));

            data.extras.contactFields = [];
            data.extras.addressFields = {};
            data.extras.openHoursFields = {};
            data.extras.information = {};
            this.collection.each(function (model) {
                tmpAddressFieldsName = model.getAddressFieldName();
                tmpModelData = model.toJSON();
                tmpModelData._viewUID = model.getAddressUID();
                tmpModelData._viewFieldName = tmpAddressFieldsName;
                console.log(tmpAddressFieldsName);
                if (/ShopName|^Address/.test(tmpAddressFieldsName)) {
                    data.extras.addressFields[tmpAddressFieldsName] = tmpModelData;
                } else if (/.*OpenHours.*/.test(tmpAddressFieldsName)) {
                    data.extras.openHoursFields[tmpAddressFieldsName] = tmpModelData;
                } else if (/Shipping|Payment/.test(tmpAddressFieldsName)) {
                    data.extras.information[tmpAddressFieldsName] = tmpModelData;
                } else {
                    data.extras.contactFields.push(tmpModelData);
                }
            });
            this.$el.html(tpl(data));
            this.$('#openhours .ediatble').editable(_.defaults({
                emptytext: '00:00 - 00:00',
                mode: 'inline'
            }, this.options.editableOptions));
            this.$('.button-label.custom-type').editable(this.options.editableOptions)
                .on('save', function (event, params) {
                    var $formGroup = $(event.target).closest('.contact-item'),
                        $control = $formGroup.find('.form-control'),
                        $menuCustomItem = $formGroup.find('.custom-contact-type');
                    $menuCustomItem.text(params.newValue);
                    $control.data('label', params.newValue).attr('data-label', params.newValue);
                });
            dfdXEditWysi.done(function () {
                that.$('.wysihtml5').editable(_.defaults({
                    mode: 'inline'
                }, that.options.editableOptions));
            });
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
            var $formGroup = $(event.target).closest('.contact-item');
            var $control = $formGroup.find('.form-control');
            $control.data('remove', 1).attr('data-remove', 1);
            $formGroup.addClass('hidden').hide();
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