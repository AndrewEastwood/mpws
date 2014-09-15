define("plugin/shop/toolbox/js/view/managerContent_Origins", [
    'default/js/lib/sandbox',
    'default/js/lib/cache',
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'default/js/lib/utils',
    'plugin/shop/toolbox/js/view/listOrigins',
    'default/js/plugin/hbs!plugin/shop/toolbox/hbs/managerContent_Origins',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/toolbox/nls/translation'
], function (Sandbox, Cache, _, Backbone, Utils, ViewListOrigins, tpl, lang) {

    var ManagerContent_Products = ViewListOrigins.extend({
        className: 'panel panel-default shop_managerContent_Origins',
        template: tpl,
        lang: lang,
        initialize: function (options) {
            ViewListOrigins.prototype.initialize.call(this);
            this.setOptions(options);
            this.grid.emptyText = lang.pluginMenu_Origins_Grid_noData;
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
        showLoading: function () {
            var self = this;
            setTimeout(function(){
                console.log('adding spinner');
                self.$('.fa-plus').addClass('fa-spin');
            }, 0);
        },
        render: function () {
            // debugger;
            if (this.$el.is(':empty')) {
                this.$el.html(tpl(Utils.getHBSTemplateData(this)));
                if (this.collection.length) {
                    this.$('.origins').append(this.grid.render().$el);
                    this.$('.origins').append(this.paginator.render().$el);
                } else {
                    this.$('.origins').html(this.grid.emptyText);
                }
            }
            return this;
        }
    });

    return ManagerContent_Products;

});