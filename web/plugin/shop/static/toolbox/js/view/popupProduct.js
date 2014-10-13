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
    'default/js/lib/select2/select2',
    'default/js/lib/jquery.fileupload/jquery.fileupload',
    'default/js/lib/jquery.fileupload/vendor/canvas-to-blob',
    'default/js/lib/jquery.fileupload/vendor/JavaScript-Load-Image/load-image',
    'default/js/lib/jquery.fileupload/vendor/jquery.ui.widget',
    'default/js/lib/jquery.fileupload/jquery.iframe-transport',
    'default/js/lib/jquery.fileupload/jquery.fileupload-validate',
    'default/js/lib/jquery.fileupload/jquery.fileupload-image',
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
                            Model: self.$('#model').val(),
                            Price: parseFloat(self.$('#price').val(), 10),
                            Description: self.$('#description').val(),
                            IsPromo: self.$('#ispromo').is(':checked'),
                            Tags: self.$('#tags').val(),
                            ISBN: self.$('#isbn').val(),
                            Features: self.$(".features-list").select2('val'),
                            file1: self.$('#file1').val(),
                            file2: self.$('#file2').val(),
                            file3: self.$('#file3').val(),
                            file4: self.$('#file4').val(),
                            file5: self.$('#file5').val()
                        }, {
                            silent: true,
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
            this.$title.html(_getTitle(this.model.id));
            this.$el.html(tpl(Utils.getHBSTemplateData(this)));

            if (!this.$dialog.isOpened()) {
                this.$dialog.open();
            }

            var _initCategory = {};
            var _initOrigin = {};
            if (!this.model.isNew()) {
                _initCategory = {
                    ID: this.model.get('CategoryID'),
                    Text: this.model.get('CategoryName')
                };
                _initOrigin = {
                    ID: this.model.get('OriginID'),
                    Text: this.model.get('OriginName')
                };
            } else {
                _initCategory = Cache.getOnce('mpwsShopPopupProductInitCategory') || _initCategory;
                _initOrigin = Cache.getOnce('mpwsShopPopupProductInitOrigin') || _initOrigin;

                if (_.isString(_initCategory)) {
                    _initCategory = _initCategory.split(';;');
                    if (_initCategory.length === 2) {
                        _initCategory = {
                            ID: parseInt(_initCategory[0], 10),
                            Text: _initCategory[1]
                        };
                    }
                }
                if (_.isString(_initOrigin)) {
                    _initOrigin = _initOrigin.split(';;');
                    if (_initOrigin.length === 2) {
                        _initOrigin = {
                            ID: parseInt(_initOrigin[0], 10),
                            Text: _initOrigin[1]
                        };
                    }
                }
            }
            var _features = this.model.get('_features');

            var _resultsFeatures = _(_features).map(function (item, id) {
                return {
                    id: id,
                    text: item
                };
            });
            var _selectCategory = this.$('#category').select2({
                placeholder: _initCategory.ID ? false : 'Виберіть категорію',
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
                },
                initSelection: function (element, callback) {
                    if (_initCategory.ID >= 0) {
                        callback({
                            id: _initCategory.ID,
                            text: _initCategory.Text
                        });
                    }
                }
            });
            var _selectOrigins = this.$('#origin').select2({
                placeholder: _initOrigin.ID ? false : 'Виберіть виробника',
                ajax: {
                    url: APP.getApiLink({
                        source: 'shop',
                        fn: 'origins'
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
                },
                initSelection: function (element, callback) {
                    if (_initOrigin.ID >= 0) {
                        callback({
                            id: _initOrigin.ID,
                            text: _initOrigin.Text
                        });
                    }
                }
            });

            if (_initOrigin.ID >= 0) {
                this.$('#origin').select2('val', _initOrigin.ID);
            }
            if (_initCategory.ID >= 0) {
                this.$('#category').select2('val', _initCategory.ID);
            }

            this.$('#price').maskMoney({
                suffix: 'грн.',
                thousands: ' ',
                decimal: '.',
                precision: 0
            });
            this.$('#price').maskMoney('mask');

            this.$('#tags').tagsinput();

            this.$(".features-list").select2({
                tags: _resultsFeatures,
                maximumInputLength: 10
            }).on('change', function (event) {
                if (event.removed) {
                    return;
                }
                var element = _(_resultsFeatures).findWhere({
                    text: event.added.text
                });

                if (element && element.id && element.id !== event.added.id) {
                    var values = $(this).select2('val');
                    values.pop();
                    $(this).select2('val', values);
                }
            });

            var features = this.model.get('Features');
            if (features) {
                this.$(".features-list").select2('val', _(features).keys());
            }

            // init uploads
            // this.$('.uploads').html(_testUploadTpl());
            // _testUploadInitFn(this);
            this.setupFileUploadItem(this.$('.product-image'));
        },
        setupFileUploadItem: function ($items) {
            var self = this;
            $items.fileupload({
                url: APP.getUploadUrl({
                    source: 'shop',
                    realm: 'products'
                }),
                dataType: 'json',
                autoUpload: true,
                limitMultiFileUploads: 1,
                maxNumberOfFiles: 2,
                acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
                maxFileSize: 5000000, // 5 MB
                // Enable image resizing, except for Android and Opera,
                // which actually support image resizing, but fail to
                // send Blob objects via XHR requests:
                disableImageResize: /Android(?!.*Chrome)|Opera/
                    .test(window.navigator.userAgent),
                previewMaxWidth: 75,
                previewMaxHeight: 75,
                previewCrop: true
            }).on('fileuploadadd', function (e, data) {
                var $btnWrapper = $(this).parents('.upload-wrapper'),
                    $btn = $(this).parent();
                $btn.removeClass('preview error');
                $btnWrapper.find('del-image').remove();
                $btn.find('.fa-plus').addClass('hidden');
                data.context = $btn.find('.preview');
                data.context.removeClass('hidden');
            }).on('fileuploadprogressall', function (e, data) {
                var progress = parseInt(data.loaded / data.total * 100, 10);
                self.$('#progress .progress-bar').css('width', progress + '%');
            }).on('fileuploadprocessalways', function (e, data) {
                var $btn = $(this).parent(),
                    index = data.index,
                    file = data.files[index],
                    node = $(data.context);
                if (file.preview) {
                    $btn.addClass('preview');
                    node.html(file.preview);
                }
                if (file.error) {
                    $btn.addClass('error');
                    node.html($('<i/>').addClass('fa fa-warning text-danger'));
                    node.append($('<span/>').addClass('message').text(file.error));
                }
            }).on('fileuploaddone', function (e, data) {
                var $btnWrapper = $(this).parents('.upload-wrapper'),
                    $btn = $(this).parent(),
                    node = $(data.context),
                    progress = parseInt(data.loaded / data.total * 100, 10),
                    $delBtn = $('<i>').attr('class', 'fa fa-times fa-fw text-danger del-image');
                $btn.removeClass('preview error');
                self.$('#progress .progress-bar').css('width', '0%');
                console.log('done');
                $.each(data.result.files, function (index, file) {
                    if (file.url) {
                        $btn.addClass('uploaded');
                        $btn.find('.file-name').val(file.name);
                        $delBtn.on('click', function () {
                            $.ajax({
                                type: 'DELETE',
                                url: file.deleteUrl
                            }).done(function () {
                                $btn.removeClass('preview error');
                                node.empty().addClass('hidden');
                                $btn.find('.fa-plus').removeClass('hidden');
                                var $input = $btn.find('.product-image').clone();
                                $btn.find('.product-image').remove();
                                $delBtn.remove();
                                self.setupFileUploadItem($input);
                                $btn.append($input);
                            });
                        });
                    } else if (file.error) {
                        $btn.addClass('error');
                        node.html($('<i/>').addClass('fa fa-warning text-danger'));
                        node.append($('<span/>').addClass('message').text(file.error));
                        $delBtn.on('click', function () {
                            $(this).remove();
                            $btn.removeClass('preview error');
                            node.empty().addClass('hidden');
                            $btn.find('.fa-plus').removeClass('hidden');
                            var $input = $btn.find('.product-image').clone();
                            $btn.find('.product-image').remove();
                            $btn.append($input);
                            self.setupFileUploadItem($input);
                        });
                    }
                    $btnWrapper.append($delBtn);
                });
            });
        }
    });

    return PopupProduct;
});