define("plugin/shop/toolbox/js/view/editCategory", [
    'default/js/lib/sandbox',
    'default/js/lib/backbone',
    'plugin/shop/toolbox/js/model/category',
    'default/js/lib/utils',
    'default/js/lib/bootstrap-dialog',
    'default/js/lib/bootstrap-alert',
    /* template */
    'default/js/plugin/hbs!plugin/shop/toolbox/hbs/editCategory',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/toolbox/nls/translation',
    'default/js/lib/select2/select2',
    'default/js/lib/jquery.fileupload/jquery.fileupload',
    'default/js/lib/jquery.fileupload/vendor/canvas-to-blob',
    'default/js/lib/jquery.fileupload/vendor/JavaScript-Load-Image/load-image',
    'default/js/lib/jquery.fileupload/vendor/jquery.ui.widget',
    'default/js/lib/jquery.fileupload/jquery.iframe-transport',
    'default/js/lib/jquery.fileupload/jquery.fileupload-validate',
    'default/js/lib/jquery.fileupload/jquery.fileupload-image'
], function (Sandbox, Backbone, ModelCategory, Utils, BootstrapDialog, BSAlert, tpl, lang) {

    function _getTitle(isNew) {
        if (isNew) {
            return $('<span/>').addClass('fa fa-asterisk').append(' ', lang.popup_category_title_new);
        } else {
            return $('<span/>').addClass('fa fa-pencil').append(' ', lang.popup_category_title_edit);
        }
    }

    var EditCategory = Backbone.View.extend({
        template: tpl,
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
                message: $(tpl(Utils.getHBSTemplateData(this))),
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
                        that.model.save({
                            Name: that.$('#name').val(),
                            Description: that.$('#description').val(),
                            file1: that.$('#file1').val(),
                            ParentID: parseInt(that.$('#parent').val(), 10)
                        }, {
                            patch: true,
                            success: function (model, response) {
                                if (!response || !response.success) {
                                    that.render();
                                } else {
                                    Backbone.history.navigate(APP.instances.shop.urls.contentList, true);
                                }
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

            var categoriesUrl = APP.getApiLink({
                source: 'shop',
                fn: 'categories'
            });
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

            this.setupFileUploadItem(this.$('.temp-upload-image'));
            return this;
        },
        restoreImage: function (event) {
            var $btn = $(event.target).parents('.upload-wrapper'),
                $fileName = $btn.find('.file-name');
            $fileName.val($fileName.data('original'));
            this.refreshUploadButton(event.target);
        },
        removeImage: function (event) {
            var that = this,
                $btn = $(event.target).parents('.upload-wrapper'),
                $prevTemp = $btn.find('.preview-image'),
                $fileName = $btn.find('.file-name'),
                delUrlForTempImage = $prevTemp.data('delete-url');
            if (delUrlForTempImage) {
                $.ajax({
                    type: 'DELETE',
                    url: delUrlForTempImage
                }).always(function () {
                    $prevTemp.empty();
                    $prevTemp.data('delete-url', null);
                    $fileName.val($fileName.data('original'));
                    that.refreshUploadButton(event.target);
                });
            } else {
                $fileName.val('');
                that.refreshUploadButton(event.target);
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

            return $btn;
        },
        setupFileUploadItem: function ($items) {
            var that = this;
            $items.each(function () {
                that.refreshUploadButton($(this));
            });
            $items.fileupload({
                url: APP.getUploadUrl(),
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
                that.refreshUploadButton($(this));
            }).on('fileuploadprogressall', function (e, data) {
                var progress = parseInt(data.loaded / data.total * 100, 10);
                that.$('#progress .progress-bar').css('width', progress + '%');
            }).on('fileuploadprocessalways', function (e, data) {
                var $btn = that.refreshUploadButton($(this)),
                    $prevTemp = $btn.find('.preview-image'),
                    $fileName = $btn.find('.file-name'),
                    index = data.index,
                    file = data.files[index];
                if (file.preview) {
                    $prevTemp.html(file.preview);
                } else {
                    $btn.addClass('error');
                    $prevTemp.empty();
                    $prevTemp.data('delete-url', null);
                    $fileName.val($fileName.data('original'));
                }
            }).on('fileuploaddone', function (e, data) {
                var $btn = $(this).parents('.upload-wrapper'),
                    $fileName = $btn.find('.file-name'),
                    $prevTemp = $btn.find('.preview-image'),
                    progress = parseInt(data.loaded / data.total * 100, 10);
                that.$('#progress .progress-bar').css('width', '0%');
                $.each(data.result.files, function (index, file) {
                    if (file.url) {
                        $prevTemp.data('delete-url', file.deleteUrl);
                        // set new uploaded file name and delete url
                        $fileName.val(file.name);
                    } else {
                        $prevTemp.empty();
                        $prevTemp.data('delete-url', null);
                        $fileName.val($fileName.data('original'));
                    }
                });
                that.refreshUploadButton($(this));
            });
        }
    });

    return EditCategory;

});