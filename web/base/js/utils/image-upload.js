define([
    'jquery',
    'backbone',
    'jquery.fileupload',
    'jquery.fileupload-image',
    'jquery.fileupload-validate'
], function ($, Backbone){
    'use strict';
    // debugger
    var Widget = Backbone.View.extend({
        events: {
            'click .del-image': 'removeImage',
            'click .restore-image': 'restoreImage'
        },
        initialize: function (options) {
            this.options = options || {};
        },
        render: function () {
            if (this.options.selector) {
                this.setupFileUploadItem(this.$(this.options.selector));
            }
        },
        restoreImage: function (event) {
            var $btn = $(event.target).parents('.upload-wrapper'),
                $fileName = $btn.find('.file-name');
            $fileName.val($fileName.data('original'));
            this.refreshUploadButton(event.target);
        },
        removeImage: function (event) {
            var self = this,
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
                    self.refreshUploadButton(event.target);
                });
            } else {
                $fileName.val('');
                self.refreshUploadButton(event.target);
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
            var self = this;
            $items.each(function () {
                self.refreshUploadButton($(this));
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
                self.$('#progress .progress-bar').css('width', '0%');
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
                self.refreshUploadButton($(this));
            });
        }
    });

    return Widget;
});