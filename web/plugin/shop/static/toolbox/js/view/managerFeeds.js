define('plugin/shop/toolbox/js/view/managerFeeds', [
    'default/js/lib/sandbox',
    'default/js/lib/backbone',
    'default/js/lib/utils',
    /* collection */
    "plugin/shop/toolbox/js/collection/feeds",
    /* template */
    'default/js/plugin/hbs!plugin/shop/toolbox/hbs/managerFeeds',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/toolbox/nls/translation'
], function (Sandbox, Backbone, Utils, CollectionFeeds, tpl, lang) {

    var ManagerFeeds = Backbone.View.extend({
        template: tpl,
        lang: lang,
        className: 'shop-manager-feeds',
        events: {
        },
        initialize: function (options) {
            this.options = options || {};
            this.collection = new CollectionFeeds();
            this.listenTo(this.collection, 'reset', this.render);
        },
        render: function () {
            this.$el.html(tpl(Utils.getHBSTemplateData(this)));
            return this;
        }
    });

    return ManagerFeeds;
});