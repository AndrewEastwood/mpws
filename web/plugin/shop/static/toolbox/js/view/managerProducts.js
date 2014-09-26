define("plugin/shop/toolbox/js/view/managerProducts", [
    'default/js/lib/sandbox',
    'default/js/lib/cache',
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'default/js/lib/utils',
    'plugin/shop/toolbox/js/view/listProducts',
    'default/js/plugin/hbs!plugin/shop/toolbox/hbs/managerProducts',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/toolbox/nls/translation',
    'default/js/lib/bootstrap-tagsinput'
], function (Sandbox, Cache, _, Backbone, Utils, ViewListProducts, tpl, lang) {

    var ManagerContent_Products = ViewListProducts.extend({
        className: 'panel panel-default shop_managerProducts',
        template: tpl,
        lang: lang,
        events: {
            'itemAdded .search': 'search',
            'itemRemoved .search': 'search'
        },
        initialize: function (options) {
            // _.bindAll(this);
            ViewListProducts.prototype.initialize.call(this);

            this.setOptions(options);

            this.collection.queryParams = _.extend({}, this.collection.queryParams, Cache.get('shop:contentProducts:request') || {});

            this.grid.emptyText = lang.pluginMenu_Products_Grid_noData_ByStatus;
            this.collection.setCustomQueryField("Status", this.options.status.toUpperCase());
            this.collection.setCustomQueryParam("Stats", true);
            this.listenTo(this.collection, 'request', $.proxy(function () {
                Cache.set('shop:contentProducts:request', this.collection.queryParams);
            }));
            this.listenTo(this.collection, 'sync', $.proxy(function (collection, resp) {
                if (this && resp.stats) {
                    this.refreshBadges(resp.stats);
                }
            }, this));
        },
        setTitle: function () {
            this.$('.title').text(lang.pluginMenu_Products_Grid_Title);
        },
        setOptions: function (options) {
            // merge with defaults
            this.options = _.defaults({}, options, {
                status: "ACTIVE"
            });
            // and adjust thme
            if (!this.options.status)
                this.options.status = "ACTIVE";
        },
        refreshBadges: function (stats) {
            var self = this;
            this.$('.tab-link .badge').html("0");
            this.$('.tab-link.products-' + this.options.status.toLowerCase()).addClass('active');
            _(stats).each(function(count, status) {
                self.$('.tab-link.products-' + status.toLowerCase() + ' .badge').html(parseInt(count, 10) || 0);
            });
        },
        showLoading: function () {
            var self = this;
            setTimeout(function(){
                console.log('adding spinner');
                self.$('.fa-plus').addClass('fa-spin');
            }, 0);
        },
        render: function () {
            // this.$('.fa-plus').removeClass('fa-spin');
            console.log('ManagerContent_Products render');
            // debugger;
            if (this.$el.is(':empty')) {
                this.$el.html(tpl(Utils.getHBSTemplateData(this)));
                // if (this.collection.length) {
                this.$('.products').append(this.grid.render().$el);
                this.$('.products').append(this.paginator.render().$el);
                // } else {
                    // this.$('.products').html(this.grid.emptyText);
                // }
                this.$('.search').tagsinput();
            }
            return this;
        },
        search: function () {
            this.collection.setCustomQueryParam("Search", $(".search").tagsinput('items'));
            this.collection.fetch();
        }
    });

    return ManagerContent_Products;

});