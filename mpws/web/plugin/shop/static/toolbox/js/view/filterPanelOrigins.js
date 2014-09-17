define("plugin/shop/toolbox/js/view/filterPanelOrigins", [
    'default/js/lib/sandbox',
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'default/js/lib/utils',
    'default/js/lib/cache',
    'plugin/shop/toolbox/js/collection/filterPanelOrigins',
    /* template */
    'default/js/plugin/hbs!plugin/shop/toolbox/hbs/filterPanelOrigins',
    'default/js/plugin/hbs!plugin/shop/toolbox/hbs/buttonMenuOriginListItem',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/toolbox/nls/translation'
], function (Sandbox, _, Backbone, Utils, Cache, CollectionOriginsFilter, tpl, tplBtnMenuMainItem, lang) {

    var FilterPanelOrigins = Backbone.View.extend({
        className: 'panel panel-default shop_filterPanelOrigins',
        template: tpl,
        lang: lang,
        events: {
            'click #show_removed': 'showRemoved'
        },
        showRemoved: function (event) {
            this.collection.fetchWithRemoved($(event.target).is(':checked'), {reset: true});
        },
        initialize: function () {
            this.collection = new CollectionOriginsFilter();
            this.listenTo(this.collection, 'reset', this.render);

            _.bindAll(this, 'saveLayout');

            Sandbox.eventSubscribe('global:route', $.proxy(function () {
                clearInterval(this.interval_saveLayout);
            }, this));
        },
        saveLayout: function () {
            console.log('saving layout filter origins');
            Cache.set("shopFilterOrdersLayoutRD", {
                scrollTopFilterOrigins: this.$('.filter-list-origins').scrollTop()
            });
        },
        restoreLayout: function () {
            var layoutConfig = Cache.get("shopFilterOrdersLayoutRD");
            layoutConfig = _.defaults({}, layoutConfig || {}, {
                scrollTopFilterOrigins: 0
            });
            this.$('.filter-list-origins').scrollTop(layoutConfig.scrollTopFilterOrigins);
            this.interval_saveLayout = setInterval(this.saveLayout, 800);
        },
        render: function () {
            // debugger;
            // var self = this;
            var _data = Utils.getHBSTemplateData(this);
            _(_data.data).each(function(item) {
                item.contextButton = tplBtnMenuMainItem(Utils.getHBSTemplateData(item));
            });
            this.$el.html(tpl(_data));
            this.$('.dropdown-toggle').addClass('btn-link');
            this.restoreLayout();
            return this;
        }
    });

    return FilterPanelOrigins;

});