define("plugin/shop/toolbox/js/view/managerContent_Products", [
    'default/js/lib/sandbox',
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'default/js/lib/utils',
    'plugin/shop/toolbox/js/view/listProducts',
    'default/js/plugin/hbs!plugin/shop/toolbox/hbs/managerContent_Products',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/toolbox/nls/translation'
], function (Sandbox, _, Backbone, Utils, ViewListProducts, tpl, lang) {

    var ManagerContent_Products = ViewListProducts.extend({
        className: 'panel panel-default shop_managerContent_Products',
        template: tpl,
        lang: lang,
        initialize: function (options) {
            // _.bindAll(this);
            ViewListProducts.prototype.initialize.call(this);

            this.setOptions(options);

            // this.on('categoryTree:changed:category', $.proxy(function () {
            //     this.showLoading();
            // }));

            // this.viewOriginsList.grid.emptyText = lang.pluginMenu_Origins_Grid_noData;
            this.grid.emptyText = lang.pluginMenu_Products_Grid_noData_ByStatus;
            this.collection.setCustomQueryField("Status", this.options.status.toUpperCase());
            this.collection.setCustomQueryParam("Stats", true);
            this.listenTo(this.collection, 'sync', $.proxy(function (collection, resp, options) {
                if (this && resp.stats)
                    this.refreshBadges(resp.stats);
            }, this));
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
            // if (this.$el.is(':empty')) {
                this.$el.html(tpl(Utils.getHBSTemplateData(this)));
                if (this.collection.length) {
                    this.$('.products').append(this.grid.render().$el);
                    this.$('.products').append(this.paginator.render().$el);
                } else {
                    this.$('.products').html(this.grid.emptyText);
                }
            // }
            return this;
        }
    });

    // var _parent = new ViewListProducts();

    // // debugger;
    // _parent.render = function () {
    //     debugger;
    //     _parent.render.call(_parent);
    //     this.$el.html(tpl(Utils.getHBSTemplateData(this)));
    //     this.$('.products').html(_parent.$el);
    //     return this;
    // }

    // ManagerContent_Products.prototype = _parent;

    // this.render = function () {
    //     console.log('ManagerContent_Products.render()');
    //     // debugger;
    //     // if (!this.isRendered) {
    //     //     ViewListProducts.prototype.render.call(ViewListProducts.prototype);
    //     //     this.isRendered = true;
    //     // }
    //     // debugger;
    //     //     this.$('.products').html(ViewListProducts.prototype.$el);
    //     // // render/refresh product list
    //     // var currentStatus = this.collection.getCustomQueryField("Status");
    //     // this.$('.tab-link.products-' + currentStatus.toLowerCase()).addClass('active');
    //     return this;
    // }
    //     lang: lang,
    //     template: tpl,
    //     isRendered: false,
    //     render: function () {
    //         console.log('ManagerContent_Products.render()');
    //         if (!this.isRendered) {
    //             ViewListProducts.prototype.render.call(ViewListProducts.prototype);
    //             this.isRendered = true;
    //         }
    //         debugger;
    //             this.$el.html(tpl(Utils.getHBSTemplateData(this)));
    //             this.$('.products').html(ViewListProducts.prototype.$el);
    //         // render/refresh product list
    //         var currentStatus = this.collection.getCustomQueryField("Status");
    //         this.$('.tab-link.products-' + currentStatus.toLowerCase()).addClass('active');
    //         return this;
    //     }
    // });

    return ManagerContent_Products;

});