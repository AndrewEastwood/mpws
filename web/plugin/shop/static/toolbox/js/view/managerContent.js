define('plugin/shop/toolbox/js/view/managerContent', [
    'default/js/lib/sandbox',
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'default/js/lib/utils',
    'default/js/lib/cache',
    'plugin/shop/toolbox/js/view/managerContent_Products',
    'plugin/shop/toolbox/js/view/filterPanelOrigins',
    'plugin/shop/toolbox/js/view/filterTreeCategories',
    /* template */
    'default/js/plugin/hbs!plugin/shop/toolbox/hbs/managerContent',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/toolbox/nls/translation',
    'default/js/lib/bootstrap-tagsinput'
], function (Sandbox, _, Backbone, Utils, Cache, ViewListProducts, ViewListOrigins, ViewCategoriesTree, tpl, lang) {

    var ManagerOrders = Backbone.View.extend({
        template: tpl,
        lang: lang,
        className: 'shop-managerContent',
        initialize: function (options) {
            // set options
            // ini sub-views
            // debugger;
            this.viewProductsList = new ViewListProducts(options);
            this.viewOriginsList = new ViewListOrigins(options);
            this.viewCatergoriesTree = new ViewCategoriesTree(options);

            // subscribe on events
            this.listenTo(this.viewProductsList.collection, 'reset', this.render);

            this.viewCatergoriesTree.on('categoryTree:changed:category', _.debounce(function (activeCategory) {
                this.viewProductsList.collection.setCustomQueryField("CategoryID", activeCategory.id);
                this.viewProductsList.collection.fetch({reset: true});
            }, 200), this);

            _.bindAll(this, 'saveLayout');

            Sandbox.eventSubscribe('global:route', $.proxy(function () {
                clearInterval(this.interval_saveLayout);
            }, this));
        },
        saveLayout: function () {
            console.log('saving layout manager content');
            Cache.set("shopManagerContentLayoutRD", {
                activeFilterTabID: this.$('.shop-managerContent-filters li.active a').attr('href').substr(1)
            });
        },
        restoreLayout: function () {
            var layoutConfig = Cache.get("shopManagerContentLayoutRD");
            layoutConfig = _.defaults({}, layoutConfig || {}, {
                activeFilterTabID: 'tree'
            });
            this.$('.nav a[href="#' + layoutConfig.activeFilterTabID + '"]').parent().addClass('active');
            this.$('.tab-pane.' + layoutConfig.activeFilterTabID).addClass('in active');
            this.interval_saveLayout = setInterval(this.saveLayout, 800);
        },
        render: function () {
            // TODO:
            // add expired and todays products
            // permanent layout and some elements
            if (this.$el.is(':empty')) {
                this.$el.html(tpl(Utils.getHBSTemplateData(this)));
                this.$('.tree').html(this.viewCatergoriesTree.$el);
                this.$('.products').html(this.viewProductsList.$el);
                this.$('.origins').html(this.viewOriginsList.$el);
                this.$('.summary input').tagsinput();
            }
            this.restoreLayout();
            return this;
        }
    });

    return ManagerOrders;

});