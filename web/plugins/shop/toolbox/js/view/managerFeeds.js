define([
    'sandbox',
    'backbone',
    'utils',
    'bootstrap-dialog',
    'plugins/shop/toolbox/js/view/feed',
    /* collection */
    "plugins/shop/toolbox/js/collection/feeds",
    /* template */
    'hbs!plugins/shop/toolbox/hbs/managerFeeds',
    /* lang */
    'i18n!plugins/shop/toolbox/nls/translation',
    'image-upload',
], function (Sandbox, Backbone, Utils, BootstrapDialog, ViewFeed, CollectionFeeds, tpl, lang) {

    var ManagerFeeds = Backbone.View.extend({
        template: Handlebars.compile(tpl), // check
        lang: lang,
        className: 'shop-manager-feeds',
        events: {
            'click .start-import': 'importFeed',
            'click .generate': 'generateFeed',
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
            this.$el.html(tpl(Utils.getHBSTemplateData(this)));
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
                    that.collection.create({
                        name: response.result.files[0].name
                    }, {
                        wait: true,
                        success: function () {
                            that.collection.fetch({reset: true});
                        }
                    });
                }
            });
            return this;
        },
        generateFeed: function (event) {
            var that = this;
            BootstrapDialog.confirm('Generate new feed?', function (rez) {
                if (rez) {
                    that.collection.generateNewProductFeed();
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
                        success: function (resp) {
                            // debugger;
                            that.collection.fetch({reset: true});
                        },
                        error: function () {
                            // debugger;
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