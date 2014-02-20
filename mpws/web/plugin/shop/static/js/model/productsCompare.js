define("plugin/shop/js/model/productsCompare", [
    'default/js/lib/sandbox',
    'default/js/model/mModel',
    'plugin/shop/js/lib/utils',
    'default/js/lib/bootstrap-dialog'
], function (Sandbox, MModel, ShopUtils, BootstrapDialog) {

    var ProductsCompare = MModel.extend({
        // Consider how to inject this
        // -=-=-=-=-=-=-=-=-=-=-=-=
        // globalEvents: {
        //     'shop:ProductsCompare:add': 'productAdd'
        // },
        initialize: function () {
            var _self = this;
            // debugger;
            this.updateUrlOptions({
                source: 'shop',
                fn: 'shop_compare',
                action: 'INFO'
            });
            MModel.prototype.initialize.call(this);

            Sandbox.eventSubscribe('shop:compare:add', function (data) {
                // debugger;
                if (data && data.id)
                    _self.productAdd(data.id);
            });
            Sandbox.eventSubscribe('shop:compare:remove', function (data) {
                // debugger;
                if (data && data.id)
                    _self.productRemove(data.id);
            });
            Sandbox.eventSubscribe('shop:compare:clear', function () {
                // debugger;
                _self.clearAll();
            });

            this.on('change', function () {
                Sandbox.eventNotify('shop:compare:info', _self.toJSON());
            });

            this.fetch();
        },
        parse: function (data) {
            if (data && data.shop && data.shop.error) {
                debugger;
                if (data.shop.error === "MaxProductsAdded")
                    BootstrapDialog.show({
                        type: BootstrapDialog.TYPE_WARNING,
                        title: 'Помилка',
                        message: "Ви можете порівнювати максимум 6 товарів."
                    });
            }

            // debugger;
            var products = ShopUtils.adjustProductEntry(data && data.shop);
            return {
                products: _(products).map(function(item){ return item; })
            };
        },
        getInfo: function () {
            this.updateUrlOptions({
                action: 'INFO'
            });
            this.fetch();
        },
        clearAll: function () {
            this.updateUrlOptions({
                action: 'CLEAR'
            });
            this.fetch();
        },
        productAdd: function (productID) {
            this.updateUrlOptions({
                action: 'ADD',
                productID: productID
            });
            this.fetch();
        },
        productRemove: function (productID) {
            this.updateUrlOptions({
                action: 'REMOVE',
                productID: productID
            });
            this.fetch();
        }
    });

    return ProductsCompare;

});