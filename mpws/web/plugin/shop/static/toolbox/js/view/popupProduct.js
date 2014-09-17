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
    'default/js/lib/select2/select2'
], function (Sandbox, Backbone, ModelProduct, Utils, Cache, BootstrapDialog, BSAlert, tpl, lang) {

    function _getTitle (isEdit) {
        if (isEdit) {
            return $('<span/>').addClass('fa fa-pencil').append(' ', lang.popup_product_title_edit);
        } else {
            return $('<span/>').addClass('fa fa-asterisk').append(' ', lang.popup_product_title_new);
        }
    }

    var OrderItem = Backbone.View.extend({
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
                cssClass: 'pluginShopProductPopup',
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
                            Name: self.$('#name').val(),
                            Description: self.$('#description').val(),
                            HomePage: self.$('#homepage').val()
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
            var self = this;

            this.$title.html(_getTitle(!!self.model.id));

            this.$el.html(tpl(Utils.getHBSTemplateData(this)));

            if (!this.$dialog.isOpened())
                this.$dialog.open();

            var _categories = self.model.get('_categories');
            var _origins = self.model.get('_origins');
            var _resultsCategories = _(_categories).map(function(item){
                return {
                    id: item.ID,
                    text: item.Name
                };
            });
            var _resultsOrigins = _(_origins).map(function(item){
                return {
                    id: item.ID,
                    text: item.Name
                };
            });

            var _selectCategory = self.$('#category').select2({
                placeholder: "Виберіть категорію",
                data: _resultsCategories
            });
            var _selectOrigins = self.$('#origin').select2({
                placeholder: "Виберіть виробника",
                data: _resultsOrigins
            });

            var _initCategoryID = null;
            var _initOriginID = null;

            if (!self.model.isNew()) {
                _initCategoryID = self.model.get('CategoryID');
                _initOriginID = self.model.get('OriginID');
            } else {
                _initCategoryID = Cache.getOnce('mpwsShopPopupProductCategoryID');
                _initOriginID = Cache.getOnce('mpwsShopPopupProductOriginID');
            }

            if (_initCategoryID)
                _selectCategory.select2("val", _initCategoryID).val(_initCategoryID);
            if (_initOriginID)
                _selectOrigins.select2("val", _initCategoryID).val(_initOriginID);
        }
    });

    return OrderItem;

});