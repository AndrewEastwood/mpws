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
        events: {
            'click .del-image': 'removeImage',
            'click .restore-image': 'restoreImage'
        },
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
            this.setupFileUploadItem(this.$('.temp-upload-image'));
        },
        restoreImage: function (event) {
            var $btn = $(event.target).parents('.upload-wrapper'),
                // $delBtn = $btn.find('.del-image'),
                // $restoreBtn = $btn.find('.restore-image'),
                // $prevImage = $btn.find('.uploaded-image'),
                $fileName = $btn.find('.file-name');
            // $btn.removeClass('original temp preview error');
            // if ($prevImage.length && $fileName.data('original') === $prevImage.attr('src')) {
            $fileName.val($fileName.data('original'));
            // }

            // $delBtn.removeClass('hidden');
            // $restoreBtn.addClass('hidden');
            this.refreshUploadButton(event.target);
        },
        removeImage: function (event) {
            var self = this,
                $btn = $(event.target).parents('.upload-wrapper'),
                // $delBtn = $btn.find('.del-image'),
                $prevTemp = $btn.find('.preview-image'),
                // $prevImage = $btn.find('.uploaded-image'),
                $fileName = $btn.find('.file-name'),
                delUrlForTempImage = $prevTemp.data('delete-url');
            // $btn.removeClass('original temp preview error');
            if (delUrlForTempImage) {
                $.ajax({
                    type: 'DELETE',
                    url: delUrlForTempImage
                }).always(function () {
                    // after temp image is removed we restore original image
                    // if ($prevImage.length) {
                    //     $btn.addClass('original');
                    //     $prevImage.removeClass('hidden');
                    // } else {
                    //     // or just hide close button
                    //     $delBtn.addClass('hidden');
                    // }
                    $prevTemp.empty();
                    $prevTemp.data('delete-url', null);
                    $fileName.val($fileName.data('original'));
                    self.refreshUploadButton(event.target);
                });
            } else {
                // $prevImage.addClass('hidden');
                $fileName.val('');
                self.refreshUploadButton(event.target);
                // $delBtn.addClass('hidden');
            }
        },
        refreshUploadButton: function (el) {
            var $btn = $(el).parents('.upload-wrapper'),
                $delBtn = $btn.find('.del-image'),
                $restoreBtn = $btn.find('.restore-image'),
                $prevTemp = $btn.find('.preview-image'),
                $prevImage = $btn.find('.uploaded-image'),
                $fileName = $btn.find('.file-name'),
                $uploadFile = $btn.find('.temp-upload-image');
            $btn.removeClass('none restore original temp preview error');
            // show restore button
            if ($fileName.val() === '' && $fileName.data('original')) {
                $btn.addClass('restore');
            }
            if ($prevTemp.data('delete-url') && $fileName.val() && $fileName.val() !== $fileName.data('original')) {
                $btn.addClass('temp');
            }
            if ($fileName.val() && $fileName.val() === $fileName.data('original')) {
                $btn.addClass('original');
            }
            if ($uploadFile.val() !== '') {
                $btn.addClass('changed');
            }
            if (!$prevTemp.data('delete-url') && $uploadFile.val() === '' && $fileName.val() === '' && !$fileName.data('original')) {
                $btn.addClass('none');
            }

            // clear errors
            // this.$('.upload-errors').empty();

            return $btn;
        },
        setupFileUploadItem: function ($items) {
            var self = this;
            $items.each(function () {
                self.refreshUploadButton($(this));
            });
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
                // var $btn = $(this).parents('.upload-wrapper'),
                //     $prevTemp = $btn.find('.preview-image'),
                //     $prevImage = $btn.find('.uploaded-image');
                // $btn.removeClass('preview error');
                // $prevTemp.addClass('hidden');
                // $prevImage.addClass('hidden');
                self.refreshUploadButton($(this));
            }).on('fileuploadprogressall', function (e, data) {
                var progress = parseInt(data.loaded / data.total * 100, 10);
                self.$('#progress .progress-bar').css('width', progress + '%');
            }).on('fileuploadprocessalways', function (e, data) {
                var $btn = self.refreshUploadButton($(this)),
                    $prevTemp = $btn.find('.preview-image'),
                    $fileName = $btn.find('.file-name'),
                    index = data.index,
                    file = data.files[index];

                // var $btn = $(this).parents('.upload-wrapper'),
                if (file.preview) {
                    // $btn.addClass('preview');
                    // $prevTemp.removeClass('hidden');
                    $prevTemp.html(file.preview);
                } else {
                    /*if (file.error) {*/
                    $btn.addClass('error');
                    $prevTemp.empty();
                    $prevTemp.data('delete-url', null);
                    $fileName.val($fileName.data('original'));
                    // $prevTemp.addClass('hidden');
                    // $prevTemp.empty();
                    // self.$('.upload-errors').text(file.error);
                }
            }).on('fileuploaddone', function (e, data) {
                var $btn = $(this).parents('.upload-wrapper'),
                    // $delBtn = $btn.find('.del-image'),
                    $fileName = $btn.find('.file-name'),
                    $prevTemp = $btn.find('.preview-image'),
                    // $prevImage = $btn.find('.uploaded-image'),
                    progress = parseInt(data.loaded / data.total * 100, 10);
                // $btn.removeClass('preview error');
                self.$('#progress .progress-bar').css('width', '0%');
                // console.log('done');
                // $delBtn.removeClass('hidden');
                $.each(data.result.files, function (index, file) {
                    if (file.url) {
                        // $btn.addClass('temp');
                        $prevTemp.data('delete-url', file.deleteUrl);
                        // set new uploaded file name and delete url
                        $fileName.val(file.name);
                    } else { /*if (file.error)*/
                        // $btn.addClass('error');
                        // $prevTemp.addClass('hidden');
                        $prevTemp.empty();
                        $prevTemp.data('delete-url', null);
                        $fileName.val($fileName.data('original'));
                        // self.$('.upload-errors').text(file.error);
                        // var prevImage = ;
                        // if (preview) {
                        // $prevImage.removeClass('hidden');
                        // } else {
                        // $fileName.val('');
                        // $delBtn.addClass('hidden');
                        // }
                    }
                });
                self.refreshUploadButton($(this));
            });
        }
    });

    return PopupProduct;
});