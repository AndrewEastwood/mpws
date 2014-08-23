define("plugin/shop/toolbox/js/view/statsProductsPopular", [
    'default/js/lib/backbone',
    'plugin/shop/toolbox/js/collection/statsProductsPopular',
    'plugin/shop/toolbox/js/view/listProducts',
    'default/js/lib/utils',
    /* template */
    'default/js/plugin/hbs!plugin/shop/toolbox/hbs/statsProductsPopular',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/toolbox/nls/translation'
], function (Backbone, CollectionProductsPopular, ViewListProducts, Utils, tpl, lang) {

    return Backbone.View.extend({
        className: 'panel panel-default',
        lang: lang,
        template: tpl,
        initialize: function () {
            this.collection = new CollectionProductsPopular();
            this.list = new ViewListProducts({collection: this.collection});
            // delete listView.columns.columnStatus;
            // delete this.list.columns.columnShipping;
            // delete this.list.columns.columnWarehouse;
            delete this.list.columns.columnDateUpdated;
            delete this.list.columns.columnDateCreated;

            this.list.columns.SoldTotal = {
                name: "SoldTotal",
                label: "Продано",
                cell: "string",
                editable: false
            };
            this.list.columns.SumTotal = {
                name: "SumTotal",
                label: "На суму",
                cell: "string",
                editable: false,
                formatter: {
                    fromRaw: function (value) {
                        return value + ' грн.';
                    }
                }
            };

            this.listenTo(this.collection, 'update reset', this.render);
        },
        render: function () {
            // debugger;
            // adjust columns
            // render into panel body
            this.$el.html(tpl(Utils.getHBSTemplateData(this)));
            if (this.collection.length)
                this.$('.panel-body').html(this.list.$el);
            this.list.render();
            return this;
        }
    });
});