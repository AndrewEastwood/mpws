define('plugin/shop/toolbox/js/view/managerFeeds', [
    'default/js/lib/sandbox',
    'default/js/lib/backbone',
    'default/js/lib/utils',
    /* collection */
    "plugin/shop/toolbox/js/collection/feeds",
    /* template */
    'default/js/plugin/hbs!plugin/shop/toolbox/hbs/managerFeeds',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/toolbox/nls/translation',
    'default/js/lib/jquery.fileupload/vendor/jquery.ui.widget',
    'default/js/lib/jquery.fileupload/jquery.iframe-transport',
    'default/js/lib/jquery.fileupload/jquery.fileupload-validate',
    'default/js/lib/jquery.fileupload/jquery.fileupload',
], function (Sandbox, Backbone, Utils, CollectionFeeds, tpl, lang) {

    var ManagerFeeds = Backbone.View.extend({
        template: tpl,
        lang: lang,
        className: 'shop-manager-feeds',
        events: {
            'click .upload-feed': 'uploadFeed'
        },
        initialize: function (options) {
            this.options = options || {};
            this.collection = new CollectionFeeds();
            this.listenTo(this.collection, 'reset', this.render);
        },
        render: function () {
            var that = this;
            this.$el.html(tpl(Utils.getHBSTemplateData(this)));
            this.$('#fileFeed').fileupload({
                url: APP.getUploadUrl({
                    source: 'shop',
                    fn: 'feeds'
                }),
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
                that.collection.fetch({reset: true})
            });
            return this;
        },
        uploadFeed: function () {

        }
    });

    return ManagerFeeds;
});