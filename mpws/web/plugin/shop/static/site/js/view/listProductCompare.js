define("plugin/shop/site/js/view/listProductCompare", [
    'default/js/lib/backbone',
    'plugin/shop/site/js/collection/listProductCompare',
    'default/js/lib/utils',
    'default/js/plugin/hbs!plugin/shop/site/hbs/listProductCompare',
    "default/js/lib/jquery.cookie"
], function (Backbone, compareCollectionInstance, Utils, tpl) {

    var ListProductCompare = Backbone.View.extend({
        collection: compareCollectionInstance,
        className: 'row shop-products-compare clearfix',
        id: 'shop-products-compare-ID',
        template: tpl,
        initialize: function () {
            this.listenTo(this.collection, 'reset', this.render);
            this.listenTo(this.collection, 'sync', this.render);
        },
        render: function() {
            this.$el.html(this.template(Utils.getHBSTemplateData(this)));
            return this;
        }
    });

    return ListProductCompare;

});