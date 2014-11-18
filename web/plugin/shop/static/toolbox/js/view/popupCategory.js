define("plugin/shop/toolbox/js/view/popupCategory", [
    'default/js/lib/sandbox',
    'default/js/lib/backbone',
    'plugin/shop/toolbox/js/model/category',
    'default/js/lib/utils',
    'default/js/lib/bootstrap-dialog',
    'default/js/lib/bootstrap-alert',
    /* template */
    'default/js/plugin/hbs!plugin/shop/toolbox/hbs/popupCategory',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/toolbox/nls/translation',
    'default/js/lib/select2/select2'
], function (Sandbox, Backbone, ModelCategory, Utils, BootstrapDialog, BSAlert, tpl, lang) {

    function _getTitle(isNew) {
        if (isNew) {
            return $('<span/>').addClass('fa fa-asterisk').append(' ', lang.popup_category_title_new);
        } else {
            return $('<span/>').addClass('fa fa-pencil').append(' ', lang.popup_category_title_edit);
        }
    }

    var PopupCategory = Backbone.View.extend({
        template: tpl,
        lang: lang,
        initialize: function () {
            var self = this;
            this.model = new ModelCategory();
            this.listenTo(this.model, 'change', this.render);
            this.$title = $('<span/>');
            this.$dialog = new BootstrapDialog({
                title: this.$title,
                message: this.$el,
                cssClass: 'pluginShopCategoryPopup',
                buttons: [{
                    label: lang.popup_category_button_Close,
                    cssClass: 'btn-default btn-link',
                    action: function (dialog) {
                        dialog.close();
                    }
                }, {
                    label: lang.popup_category_button_Save,
                    cssClass: 'btn-success btn-outline',
                    action: function (dialog) {
                        self.model.save({
                            Name: self.$('#name').val(),
                            Description: self.$('#description').val(),
                            ParentID: parseInt(self.$('#parent').val(), 10)
                        }, {
                            patch: true,
                            success: function (model, response) {
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
            this.$title.html(_getTitle(this.model.isNew()));
            this.$el.html(tpl(Utils.getHBSTemplateData(this)));
            if (!this.$dialog.isOpened())
                this.$dialog.open();

            var _select = this.$('#parent').select2({
                placeholder: "Без батьківської категорії",
                ajax: {
                    url: APP.getApiLink({
                        source: 'shop',
                        fn: 'categories'
                    }),
                    results: function (data, page) {
                        var _results = _(data.items).map(function (item) {
                            return {
                                id: item.ID,
                                text: item.Name
                            };
                        });
                        return {
                            results: _results
                        };
                    }
                }
            });

            if (!this.model.isNew()) {
                _select.val(this.model.get('ParentID'), true);
            }

        }
    });

    return PopupCategory;

});