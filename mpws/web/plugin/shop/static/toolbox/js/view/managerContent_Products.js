define("plugin/shop/toolbox/js/view/managerContent_Products", [
    'default/js/lib/sandbox',
    'default/js/lib/backbone',
    'default/js/lib/utils',
    'plugin/shop/toolbox/js/view/listProducts',
    'default/js/plugin/hbs!plugin/shop/toolbox/hbs/managerContent_Products',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/toolbox/nls/translation'
], function (Sandbox, Backbone, Utils, ViewListProducts, tpl, lang) {

    var ManagerContent_Products = ViewListProducts.extend({
        className: 'panel panel-default shop_managerContent_Products',
        lang: lang,
        template: tpl,
        render: function () {
            ViewListProducts.prototype.render.call(this);
            var $ctn = $(tpl(Utils.getHBSTemplateData(this)));
            $ctn.find('.products').html(this.$el);
            this.$el = $ctn;
            // render/refresh product list
            var currentStatus = this.collection.getCustomQueryField("Status");
            this.$('.tab-link.products-' + currentStatus.toLowerCase()).addClass('active');
            return this;
        }
    });

    return ManagerContent_Products;

});