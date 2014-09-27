define("plugin/shop/toolbox/js/view/popupProduct", [
    'default/js/lib/sandbox',
    'default/js/lib/backbone',
    'plugin/shop/toolbox/js/model/product',
    'default/js/lib/utils',
    'default/js/lib/cache',
    'default/js/lib/bootstrap-dialog',
    'default/js/lib/bootstrap-alert',
    /* template */
    'default/js/plugin/hbs!plugin/shop/toolbox/hbs/popupProduct',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/toolbox/nls/translation',
    'default/js/lib/select2/select2',
    'default/js/lib/jquery.maskMoney',
    'default/js/lib/bootstrap-tagsinput',
    'default/js/lib/select2/select2'
], function (Sandbox, Backbone, ModelProduct, Utils, Cache, BootstrapDialog, BSAlert, tpl, lang) {

    function _getTitle(isEdit) {
        if (isEdit) {
            return $('<span/>').addClass('fa fa-pencil').append(' ', lang.popup_product_title_edit);
        } else {
            return $('<span/>').addClass('fa fa-asterisk').append(' ', lang.popup_product_title_new);
        }
    }

    var PopupProduct = Backbone.View.extend({
        template: tpl,
        lang: lang,
        initialize: function () {
            var self = this;
            this.model = new ModelProduct();
            this.listenTo(this.model, 'change', this.render);
            this.$title = $('<span/>');
            this.$dialog = new BootstrapDialog({
                title: this.$title,
                message: this.$el,
                cssClass: 'shop-popup-product',
                buttons: [{
                    label: lang.popup_product_button_Close,
                    cssClass: 'btn-default btn-link',
                    action: function (dialog) {
                        dialog.close();
                    }
                }, {
                    label: lang.popup_product_button_Save,
                    cssClass: 'btn-success btn-outline',
                    action: function (dialog) {
                        self.model.save({
                            CategoryID: parseInt(self.$('#category').select2('val'), 10),
                            OriginID: parseInt(self.$('#origin').select2('val'), 10),
                            Name: self.$('#name').val(),
                            Model: self.$('#price').val(),
                            Price: parseFloat(self.$('#price').val(), 10),
                            Description: self.$('#description').text(),
                            IsPromo: self.$('#IsPromo').is(':checked'),
                            Tags: self.$('#price').val(),
                            Features: self.$(".features-list").select2('val')
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
            this.$title.html(_getTitle(this.model.id));
            this.$el.html(tpl(Utils.getHBSTemplateData(this)));

            if (!this.$dialog.isOpened()) {
                this.$dialog.open();
            }

            var _initCategoryID = null;
            var _initOriginID = null;
            var _categories = this.model.get('_categories');
            var _origins = this.model.get('_origins');
            var _features = this.model.get('_features');
            var _resultsCategories = _(_categories).map(function (item) {
                return {
                    id: item.ID,
                    text: item.Name
                };
            });
            var _resultsOrigins = _(_origins).map(function (item) {
                return {
                    id: item.ID,
                    text: item.Name
                };
            });
            var _resultsFeatures = _(_features).map(function (item, id) {
                return item;
            });
            var _selectCategory = this.$('#category').select2({
                placeholder: "Виберіть категорію",
                data: _resultsCategories
            });
            var _selectOrigins = this.$('#origin').select2({
                placeholder: "Виберіть виробника",
                data: _resultsOrigins
            });
            if (!this.model.isNew()) {
                _initCategoryID = this.model.get('CategoryID');
                _initOriginID = this.model.get('OriginID');
            } else {
                _initCategoryID = Cache.getOnce('mpwsShopPopupProductCategoryID');
                _initOriginID = Cache.getOnce('mpwsShopPopupProductOriginID');
            }

            if (_initCategoryID)
                _selectCategory.select2("val", _initCategoryID).val(_initCategoryID);
            if (_initOriginID)
                _selectOrigins.select2("val", _initOriginID).val(_initOriginID);

            this.$('#price').maskMoney({
                suffix: 'грн.',
                thousands: ' ',
                decimal: ','
            });

            this.$('#tags').tagsinput();

            this.$(".features-list").select2({
                tags: _resultsFeatures,
                maximumInputLength: 10
            });
        }
    });

    return PopupProduct;
});