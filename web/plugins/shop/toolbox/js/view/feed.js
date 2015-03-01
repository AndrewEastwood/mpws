define([
    'backbone',
    'utils',
    /* template */
    'hbs!plugins/shop/toolbox/hbs/feed',
    /* lang */
    'i18n!plugins/shop/toolbox/nls/translation'
], function (Backbone, Utils, tpl, lang) {

    return Backbone.View.extend({
        className: 'feed-item-wrapper',
        lang: lang,
        template: tpl,
        initialize: function () {
            if (this.model) {
                this.listenTo(this.model, 'change', this.render);
                if (this.model.collection) {
                    this.listenTo(this.model.collection, 'feed:started', this.disableImportButton);
                    this.listenTo(this.model.collection, 'feed:done', this.enableImportButton);
                }
            }
        },
        render: function () {
            this.$el.html(tpl(Utils.getHBSTemplateData(this)));
            return this;
        },
        disableImportButton: function () {
            this.$('.start-import').addClass('hidden');
        },
        enableImportButton: function () {
            this.$('.start-import').removeClass('hidden');
        }
    });
});