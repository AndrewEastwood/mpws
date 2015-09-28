define([
    'backbone',
    'handlebars',
    'utils',
    'bootstrap-dialog',
    'toastr',
    'plugins/shop/toolbox/js/view/feed',
    /* collection */
    "plugins/shop/toolbox/js/collection/feeds",
    /* template */
    'text!plugins/shop/toolbox/hbs/managerFeeds.hbs',
    /* lang */
    'i18n!plugins/shop/toolbox/nls/translation',
    'image-upload',
], function (Backbone, Handlebars, Utils, BootstrapDialog, toastr, ViewFeed, CollectionFeeds, tpl, lang) {

    var ManagerFeeds = Backbone.View.extend({
        template: Handlebars.compile(tpl), // check
        lang: lang,
        className: 'shop-manager-feeds',
        events: {
            'click .start-import': 'importFeed',
            'click .js-generate-item': 'generateFeed',
            'click .delete-uploaded': 'deleteUploadedFeed',
            'click .download-import': 'downloadImportFeed',
            'click .cancel-import': 'cancelImportFeed'
        },
        initialize: function (options) {
            this.options = options || {};
            this.collection = new CollectionFeeds();
            this.listenTo(this.collection, 'reset', this.render);
        },
        render: function () {
            var that = this;
            this.$el.html(this.template(Utils.getHBSTemplateData(this)));
            this.collection.each(function (feedModel) {
                var feedView = new ViewFeed({model: feedModel});
                feedView.render();
                feedView.$el.addClass('list-group-item');
                if (feedModel.isGenerated()) {
                    that.$('.feeds-generated').append(feedView.$el);
                } else {
                    that.$('.feeds-uploaded').append(feedView.$el);
                }
            });
            this.$('#fileFeed').fileupload({
                url: APP.getUploadUrl(),
                dataType: 'json',
                autoUpload: true,
                limitMultiFileUploads: 1,
                maxNumberOfFiles: 1,
                acceptFileTypes: /(\.|\/)(xls)$/i,
                maxFileSize: 15 * 1024 * 1024, // 15 MB
            }).on('fileuploadadd', function (e, data) {
            }).on('fileuploadprogressall', function (e, data) {
                var progress = parseInt(data.loaded / data.total * 100, 10);
                self.$('#progress .progress-bar').css('width', progress + '%');
            }).on('fileuploadprocessalways', function (e, data) {
            }).on('fileuploaddone', function (e, data) {
                var response = data.response();
                if (response && response.result && response.result.files && response.result.files.length) {
                    if (response.result.files[0].error) {
                        toastr.error(response.result.files[0].error);
                        return;
                    }
                    that.collection.create({
                        name: response.result.files[0].name
                    }, {
                        wait: true,
                        success: function (col, response) {
                            toastr.success('Файл додано');
                            that.collection.fetch({reset: true});
                        }
                    });
                }
            });
            return this;
        },
        generateFeed: function (event) {
            var that = this,
                $generateType = $(event.target).closest('.js-generate-item');
            if (that.generatingFeed) {
                return;
            }
            var genType = $generateType.data('type');
            if (!genType) {
                toastr.success('Невідомий тип експорту');
                return;
            }
            BootstrapDialog.confirm('Згенерувати новий "' + genType + '" фід?', function (rez) {
                if (rez) {
                    that.generatingFeed = true;
                    that.$('.js-generate-menu').addClass('hidden');
                    that.$('.js-spinner-generate').removeClass('hidden');
                    that.collection.generateNewProductFeed(genType)
                        .done(function () {
                            that.generatingFeed = false;
                            that.$('.js-generate-menu').removeClass('hidden');
                            that.$('.js-spinner-generate').addClass('hidden');
                            toastr.success('Згенеровано');
                        })
                        .fail(function () {
                            that.generatingFeed = false;
                            that.$('.js-generate-menu').removeClass('hidden');
                            that.$('.js-spinner-generate').addClass('hidden');
                            toastr.error('Помилка генератора');
                        });
                }
            });
        },
        importFeed: function (event) {
            var that = this,
                $feedItem = $(event.target).closest('.feed-item'),
                feedID = $feedItem.length && $feedItem.data('id'),
                feedModel = this.collection.get(feedID);
            if (!feedModel) {
                return;
            }
            BootstrapDialog.confirm('Import ' + feedModel.get('name') + ' feed?', function (rez) {
                if (rez) {
                    feedModel.set({
                        scheduled: 0,
                        running: 1,
                        complete: 0,
                        canceled: 0
                    });
                    feedModel.collection.trigger('feed:started');
                    feedModel.importUploadedProductFeed({
                        patch: true,
                        // contentType: 'text',
                        dataFilter: function (data) {
                            return data.replace(/[#;].*\n/g, '');
                        },
                        success: function (col, response) {
                            // debugger;
                            if (!response || !response.success) {
                                toastr.error('Помилка імпорту');
                            } else {
                                toastr.success('Імпорт завершено');
                            }
                            that.collection.fetch({reset: true});
                        },
                        error: function () {
                            // debugger;
                            toastr.success('Помилка імпорту');
                        }
                    });
                }
            });
        },
        cancelImportFeed: function (event) {
            var that = this,
                $feedItem = $(event.target).closest('.feed-item'),
                feedID = $feedItem.length && $feedItem.data('id'),
                feedModel = this.collection.get(feedID);
            if (!feedModel) {
                return;
            }
            BootstrapDialog.confirm('Cancel active import ' + feedModel.get('name') + ' feed?', function (rez) {
                if (rez) {
                    feedModel.cancelActiveImportProductFeed({
                        patch: true,
                        success: function () {
                            toastr.success('Скасовано');
                            that.collection.fetch({reset: true});
                        }
                    });
                }
            });
        },
        deleteUploadedFeed: function (event) {
            var that = this,
                $feedItem = $(event.target).closest('.feed-item'),
                feedID = $feedItem.length && $feedItem.data('id'),
                feedModel = this.collection.get(feedID);
            if (!feedModel) {
                return;
            }
            BootstrapDialog.confirm('Delete ' + feedModel.get('name') + ' feed?', function (rez) {
                if (rez) {
                    feedModel.destroy({
                        success: function () {
                            toastr.success('Видалено');
                            that.collection.fetch({reset: true});
                        }
                    });
                }
            });
        },
        downloadImportFeed: function (event) {
            var $el = $(event.target);
            if ($el.data('href')) {
                location.href = $el.data('href');
            }
        }
    });

    return ManagerFeeds;
});