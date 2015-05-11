// this is a part of managerContent so it's better to move all into manager
define([
    'cachejs',
    'underscore',
    'backbone',
    'handlebars',
    'utils',
    'plugins/shop/toolbox/js/view/listProducts',
    'text!plugins/shop/toolbox/hbs/managerProducts.hbs',
    /* lang */
    'i18n!plugins/shop/toolbox/nls/translation'
], function (Cache, _, Backbone, Handlebars, Utils, ViewListProducts, tpl, lang) {

    var ManagerContent_Products = ViewListProducts.extend({
        className: 'panel panel-default plugin-shop-manager-products',
        template: Handlebars.compile(tpl), // check
        lang: lang,
        events: {
            'itemAdded .search': 'search',
            'itemRemoved .search': 'search'
        },
        initialize: function (options) {
            this.options = options || {};
            ViewListProducts.prototype.initialize.call(this, options);
            this.grid.emptyText = lang.pluginMenu_Products_Grid_noData_ByStatus;
            if (this.options.status) {
                this.collection.setCustomQueryField("Status", this.options.status.toUpperCase());
            } else {
                this.collection.removeCustomQueryField("Status");
            }
            this.collection.setCustomQueryParam("Stats", true);

            this.listenTo(this.collection, 'sync', $.proxy(function (collection, resp) {
                this.refreshBadges(resp.stats);
                // ability to set new category by dropping product onto category node (category tree)
                this.grid.$('tbody > tr').attr({
                    draggable: true
                }).on('dragstart', function (ev) {
                    ev.originalEvent.dataTransfer.setData('productId', $(this).find('.dndrow').data('id'));
                });
            }, this));
        },
        setTitle: function () {
            this.$('.title').text(lang.pluginMenu_Products_Grid_Title);
        },
        getDisplayStatus: function () {
            var status = this.options && this.options.status || 'all';
            return status.toLowerCase();
        },
        refreshBadges: function (stats) {
            // var self = this;
            // debugger;
            // this.$('.tab-link .badge').html("0");
            this.$('.tab-link.products-' + this.getDisplayStatus()).addClass('active');
            // _(stats).each(function(count, status) {
            //     self.$('.tab-link.products-' + status.toLowerCase() + ' .badge').html(parseInt(count, 10) || 0);
            // });
        },
        startLoadingAnim: function () {
            var self = this;
            setTimeout(function(){
                // console.log('adding spinner');
                self.$('.fa-plus').addClass('fa-spin');
            }, 0);
        },
        stopLoadingAnim: function () {
            var self = this;
            setTimeout(function(){
                self.$('.fa-plus').removeClass('fa-spin');
            }, 0);
        },
        render: function () {
            // debugger;
            if (this.$el.is(':empty')) {
                var data = Utils.getHBSTemplateData(this);
                this.$el.html(this.template(data));
                this.$('.products').append(this.grid.render().$el);
                this.$('.products').append(this.paginator.render().$el);
                // debugger;
                this.$('.search').tagsinput();
            }
            if (this.collection.extras._category) {
                this.$('.category-title').removeClass('hidden');
                this.$('.category-title .text').text(this.collection.extras._category.Name);
            } else {
                this.$('.category-title').addClass('hidden');
            }
            var searchItems = this.collection.getCustomQueryParam("Search");
            // debugger;
            if (_.isArray(searchItems)) {
                this.$('.search').tagsinput('add', searchItems.join(','));
            }
            // debugger;
            this.trigger('rendered');
            return this;
        },
        search: function () {
            this.collection.setCustomQueryParam("Search", this.$(".search").tagsinput('items'));
            this.collection.fetch();
        }
    });

    return ManagerContent_Products;

});