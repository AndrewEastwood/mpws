define('plugin/shop/site/js/collection/listProductWish', [
    'default/js/lib/sandbox',
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'plugin/shop/site/js/model/product',
    'plugin/shop/common/js/lib/utils'
], function (Sandbox, _, Backbone, ModelProduct, ShopUtils) {

    var ListProductWish = Backbone.Collection.extend({
        model: ModelProduct,
        url: function (options) {
            return APP.getApiLink(_.extend({
                source: 'shop',
                fn: 'wish'}, options));
        },
        initialize: function () {
            listProductWish = this;
            _.bindAll(this, 'removeProductByID', 'addNew', 'removeAll');
            Sandbox.eventSubscribe('plugin:shop:list_wish:add', this.addNew);
            Sandbox.eventSubscribe('plugin:shop:list_wish:remove', this.removeProductByID);
            Sandbox.eventSubscribe('plugin:shop:list_wish:clear', this.removeAll);
        },
        removeProductByID:  function (data) {
            // debugger;
            var self = this;
            if (data && data.id) {
                var model = this.get(data.id);
                if (model) {
                    model.destroy({
                        url: this.url({productID: data.id}),
                        success: function (collection, resp) {
                            self.reset(self.parse(resp));
                            Sandbox.eventNotify('plugin:shop:list_wish:changed', data);
                        }
                    });
                }
            }
        },
        addNew: function (data) {
            // debugger;
            var self = this;
            if (data && data.id && !this.get(data.id)) {
                this.create({productID: data.id}, {
                    url: this.url(),
                    success: function (model, resp) {
                        self.reset(self.parse(resp));
                        Sandbox.eventNotify('plugin:shop:list_wish:changed', data);
                    }
                });
            }
        },
        removeAll: function (data) {
            var self = this;
            this.sync("delete", this, {
                url: this.url({productID: "*"}),
                success: function (collection, resp) {
                    self.reset(self.parse(resp));
                    Sandbox.eventNotify('plugin:shop:list_wish:changed', data);
                }
            });
        },
        parse: function (data) {
            // debugger;
            return _(data.items).map(function(item){ return item; });
        }
    });

    return new ListProductWish();
});