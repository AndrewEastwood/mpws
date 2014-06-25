define("plugin/shop/site/js/model/productsCompare", [
    'default/js/lib/sandbox',
    'default/js/lib/backbone',
    'plugin/shop/common/js/lib/utils',
    'default/js/lib/bootstrap-dialog'
], function (Sandbox, Backbone, ShopUtils, BootstrapDialog) {

    var ProductsCompare = Backbone.Model.extend({
        // Consider how to inject this
        // -=-=-=-=-=-=-=-=-=-=-=-=
        // globalEvents: {
        //     'shop:ProductsCompare:add': 'productAdd'
        // },
        url: function () {
            // debugger;
            return APP.getApiLink({
                source: 'shop',
                fn: 'compare'
            })
        },
        initialize: function () {
            // MModel.prototype.initialize.call(this);
            
            var _self = this;
            // debugger;

            // MModel.prototype.initialize.call(this);

            Sandbox.eventSubscribe('plugin:shop:list_compare:add', function (data) {
                // debugger;
                if (data && data.id)
                    _self.productAdd(data.id);
            });
            Sandbox.eventSubscribe('plugin:shop:list_compare:remove', function (data) {
                // debugger;
                if (data && data.id)
                    _self.productRemove(data.id);
            });
            Sandbox.eventSubscribe('plugin:shop:list_compare:clear', function () {
                // debugger;
                _self.clearAll();
            });

            this.on('change', function () {
                Sandbox.eventNotify('plugin:shop:list_compare:info', _self.toJSON());
            });

            // this.getInfo();
        },
        parse: function (data) {
            if (data.error) {
                debugger;
                if (data.shop.error === "MaxProductsAdded")
                    BootstrapDialog.show({
                        type: BootstrapDialog.TYPE_WARNING,
                        title: 'Помилка',
                        message: "Ви можете порівнювати максимум 10 товарів."
                    });
            }

            // debugger;
            var products = ShopUtils.adjustProductItem(data.items);
            return {
                products: _(products).map(function(item){ return item; })
            };
        },
        getInfo: function () {
            // this.updateUrl({
            //     action: 'INFO'
            // });
            this.fetch();
        },
        clearAll: function () {
            this.updateUrl({
                action: 'CLEAR'
            });
            this.fetch();
        },
        productAdd: function (productID) {
            this.updateUrl({
                action: 'ADD',
                productID: productID
            });
            this.fetch();
        },
        productRemove: function (productID) {
            this.updateUrl({
                action: 'REMOVE',
                productID: productID
            });
            this.fetch();
        }
    });

    return new ProductsCompare();

});