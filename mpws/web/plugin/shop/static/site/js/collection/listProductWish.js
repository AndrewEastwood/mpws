define('plugin/shop/site/js/collection/listProductWish', [
    'default/js/lib/sandbox',
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'plugin/shop/site/js/model/productItemBase',
    'plugin/shop/common/js/lib/utils'
], function (Sandbox, _, Backbone, ProductItemBase, ShopUtils) {

    var ListProductWish = Backbone.Collection.extend({
        model: ProductItemBase,
        url: function (options) {
            return APP.getApiLink(_.extend({
                source: 'shop',
                fn: 'wish'}, options));
        },
        initialize: function () {
            listProductWish = this;

            _.bindAll(this, 'removeProductByID', 'addNew', 'removeAll');

            Sandbox.eventSubscribe('plugin:shop:wishlist:add', this.addNew);
            Sandbox.eventSubscribe('plugin:shop:wishlist:remove', this.removeProductByID);
            Sandbox.eventSubscribe('plugin:shop:wishlist:clear', this.removeAll);

        },
        removeProductByID:  function (data) {
            var self = this;
            if (data && data.id) {
                var model = this.get(data.id);
                if (model) {
                    model.destroy({
                        url: this.url({productID: data.id}),
                        success: function (collection, data) {
                            self.reset(self.parse(data));
                        }
                    });
                }
            }
        },
        addNew: function (data) {
            var self = this;
            if (data && data.id && !this.get(data.id)) {
                this.create({productID: data.id}, {
                    success: function (model, data) {
                        self.reset(self.parse(data));
                    }
                });
            }
        },
        removeAll: function () {
            var self = this;
            this.sync("delete", this, {
                url: this.url({productID: "*"}),
                success: function (collection, data) {
                    self.reset(self.parse(data));
                }
            });
        },
        parse: function (data) {
            // debugger;
            var products = ShopUtils.adjustProductItems(data && data.items);
            return _(products).map(function(item){ return item; });
        }
    });

    return new ListProductWish();
});