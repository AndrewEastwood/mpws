define([
    'backbone',
    'handlebars',
    'plugins/shop/toolbox/js/model/category',
    'utils',
    'bootstrap-dialog',
    'bootstrap-alert',
    /* template */
    'text!plugins/shop/toolbox/hbs/editCategory.hbs',
    /* lang */
    'i18n!plugins/shop/toolbox/nls/translation',
    'image-upload',
    'select2',
], function (Backbone, Handlebars, ModelCategory, Utils, BootstrapDialog, BSAlert, tpl, lang, WgtImageUpload) {

    function _getTitle(isNew) {
        if (isNew) {
            return $('<span/>').addClass('fa fa-asterisk').append(' ', lang.popup_category_title_new);
        } else {
            return $('<span/>').addClass('fa fa-pencil').append(' ', lang.popup_category_title_edit);
        }
    }

    var EditCategory = Backbone.View.extend({
        template: Handlebars.compile(tpl), // check
        lang: lang,
        className: 'bootstrap-dialog type-primary size-normal plugin-shop-category',
        events: {
            'click .del-image': 'removeImage',
            'click .restore-image': 'restoreImage'
        },
        initialize: function () {
            this.model = new ModelCategory();
            this.listenTo(this.model, 'sync', this.render);
        },
        render: function () {
            var that = this;
            var $dialog = new BootstrapDialog({
                closable: false,
                draggable: false,
                title: _getTitle(this.model.isNew()),
                message: $(this.template(Utils.getHBSTemplateData(this))),
                buttons: [{
                    label: lang.popup_category_button_Close,
                    cssClass: 'btn-default btn-link',
                    action: function (dialog) {
                        Backbone.history.navigate(APP.instances.shop.urls.contentList, true);
                    }
                }, {
                    label: lang.popup_category_button_Save,
                    cssClass: 'btn-success btn-outline',
                    action: function (dialog) {
                        debugger
                        that.model.set({
                            Name: that.$('#name').val(),
                            Description: that.$('#description').val(),
                            file1: that.$('#file1').val()
                        });
                        if (that.$('#parent').val()) {
                            that.model.set('ParentID', parseInt(that.$('#parent').val(), 10));
                        }
                        that.model.save().done(function (response) {
                            if (!response || !response.success) {
                                that.render();
                            } else {
                                Backbone.history.navigate(APP.instances.shop.urls.contentList, true);
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

            var categoriesUrl = APP.getApiLink('shop', 'categories');
            $.get(categoriesUrl, function (data) {
                var _results = _(data.items).map(function (item) {
                    return {
                        id: item.ID,
                        text: item.Name
                    };
                });
                var _select = that.$('#parent').select2({
                    placeholder: "Без батьківської категорії",
                    // minimumResultsForSearch: -1,
                    data: _results
                });
                if (!that.model.isNew()) {
                    _select.val(that.model.get('ParentID'), true);
                }
            });

            // setup logo upload
            var logoUpload = new WgtImageUpload({
                el: this.$el,
                selector: '.temp-upload-image'
            });
            logoUpload.render();
            return this;
        }
    });

    return EditCategory;

});