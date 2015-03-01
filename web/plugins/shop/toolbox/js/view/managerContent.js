define([
    'sandbox',
    'underscore',
    'backbone',
    'utils',
    'cachejs',
    'plugins/shop/toolbox/js/view/managerProducts',
    'plugins/shop/toolbox/js/view/filterPanelOrigins',
    'plugins/shop/toolbox/js/view/filterTreeCategories',
    /* template */
    'hbs!plugins/shop/toolbox/hbs/managerContent',
    /* lang */
    'i18n!plugins/shop/toolbox/nls/translation'
], function (Sandbox, _, Backbone, Utils, Cache, ViewListProducts, ViewListOrigins, ViewCategoriesTree, tpl, lang) {

    var ManagerOrders = Backbone.View.extend({
        template: tpl,
        lang: lang,
        className: 'plugin-shop-content',
        initialize: function (options) {
            // set options
            // ini sub-views
            // debugger;
            var productListOptions = _.extend({}, options, {
                adjustColumns: function (columns) {
                    return _(columns).omit(
                       'columnID', 'columnDateUpdated', 'columnDateCreated',
                       'columnSKU', 'columnModel');
                }
            });

            this.viewProductsList = new ViewListProducts(productListOptions);
            this.viewOriginsList = new ViewListOrigins(options);
            this.viewCatergoriesTree = new ViewCategoriesTree(options);

            // subscribe on events
            this.listenTo(this.viewProductsList.collection, 'reset', this.render);

            this.viewCatergoriesTree.on('categoryTree:changed:category', _.debounce(function (activeCategory) {
                if (activeCategory.id < 0) {
                    // show all categories
                    this.viewProductsList.collection.setCustomQueryField("CategoryID", void(0));
                    
                } else {
                    this.viewProductsList.collection.setCustomQueryField("CategoryID", activeCategory.allIDs.join(',') + ':IN');
                }
                this.viewProductsList.collection.fetch({
                    reset: true
                });
            }, 200), this);

            _.bindAll(this, 'saveLayout');

            Sandbox.eventSubscribe('global:route', $.proxy(function () {
                clearInterval(this.interval_saveLayout);
            }, this));
        },
        saveLayout: function () {
            // console.log('saving layout manager content');
            Cache.set("shopManagerContentLayoutRD", {
                activeFilterTabID: this.$('.plugin-shop-content-filters li.active a').attr('href').substr(1)
            });
        },
        restoreLayout: function () {
            // debugger;
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
                this.viewProductsList.grid.emptyText = $('<h4>').text(lang.managerContent.products.nodata);
                this.$('.tree').html(this.viewCatergoriesTree.$el);
                this.$('.products').html(this.viewProductsList.$el);
                this.$('.origins').html(this.viewOriginsList.$el);
            }
            this.restoreLayout();
            return this;
        }
    });

    return ManagerOrders;

});