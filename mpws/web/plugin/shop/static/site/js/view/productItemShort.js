define("plugin/shop/site/js/view/productItemShort", [
    'default/js/lib/sandbox',
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'default/js/lib/utils',
    'default/js/plugin/hbs!plugin/shop/site/hbs/productItemShort'
], function (Sandbox, _, Backbone, Utils, tpl) {

    var ProductItemShort = Backbone.View.extend({
        className: 'shop-product-item shop-product-item-short col-xs-12 col-sm-6 col-md-3 col-lg-3',
        template: tpl,
        initialize: function () {
            // var self = this;
            _.bindAll(this, 'refresh');

            // Sandbox.eventSubscribe('plugin:shop:list_wish:add', this.buttonWish(true));
            // Sandbox.eventSubscribe('plugin:shop:list_wish:remove', this.buttonWish(false));
            // Sandbox.eventSubscribe('plugin:shop:list_wish:clear', this.buttonWish(false));

            // Sandbox.eventSubscribe('plugin:shop:list_compare:add', this.buttonCompare(true));
            // Sandbox.eventSubscribe('plugin:shop:list_compare:remove', this.buttonCompare(false));
            // Sandbox.eventSubscribe('plugin:shop:list_compare:clear', this.buttonCompare(false));

            Sandbox.eventSubscribe('plugin:shop:list_wish:changed', this.refresh);
            Sandbox.eventSubscribe('plugin:shop:list_compare:changed', this.refresh);

            if (this.model)
                this.listenTo(this.model, 'change', this.render);
        },
        // buttonWish: function (state) {
        //     var self = this;
        //     return function (data) {
        //         if (data && data.id && (data.id === self.model.id || data.id === "*")) {
        //             self.$('.btn-add-to-wish').toggleClass('disabled', state);
        //         }
        //     }
        // },
        // buttonCompare: function (state) {
        //     var self = this;
        //     return function (data) {
        //         // debugger;
        //         if (data && data.id && (data.id === self.model.id || data.id === "*")) {
        //             self.$('.btn-add-to-compare').toggleClass('disabled', state);
        //         }
        //     }
        // },
        refresh: function (data) {
            // debugger;
            // return function (data) {
                // debugger;
                if (this.model) {
                    if (data && data.id && (data.id === this.model.id || data.id === "*")) {
                        this.model.fetch();
                    }
                }
            // }
        },
        render: function () {
            this.$el.html(this.template(Utils.getHBSTemplateData(this)));
            return this;
        }
    });

    return ProductItemShort;

});