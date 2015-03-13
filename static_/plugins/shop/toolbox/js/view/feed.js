define([
    'backbone',
    'handlebars',
    'utils',
    /* template */
    'text!plugins/shop/toolbox/hbs/feed.hbs',
    /* lang */
    'i18n!plugins/shop/toolbox/nls/translation'
], function (Backbone, Handlebars, Utils, tpl, lang) {

    return Backbone.View.extend({
        className: 'feed-item-wrapper',
        lang: lang,
        template: Handlebars.compile(tpl), // check
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
            this.$el.html(this.template(Utils.getHBSTemplateData(this)));
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