define('plugin/shop/site/js/collection/listProductCatalog', [
    'default/js/lib/underscore',
    'default/js/collection/mCollection',
    'plugin/shop/site/js/model/productItemBase',
    'default/js/lib/url',
    'plugin/shop/common/js/lib/utils',
    'default/js/lib/jquery.cookie',
], function (_, MCollection, ProductItemBase, JSUrl, ShopUtils) {

    var Collection = MCollection.getNew();

    var ListProductCatalog = Collection.extend({
        source: 'shop',
        fn: 'shop_catalog',
        model: ProductItemBase,
        parse: function (data) {
            // adjust products
            var products = ShopUtils.adjustProductItem(data && data.shop);
            // adjust filtering
            var filter = data.shop.filter;
            var productItems = _(products).map(function(item){ return item; });

            // join category/origin info
            _(filter.filterOptionsAvailable.filter_categoryBrands).each(function(brand){
                // debugger;
                if (filter.filterOptionsApplied.filter_categoryBrands[brand.ID])
                    _.extend(brand, filter.filterOptionsApplied.filter_categoryBrands[brand.ID]);
                else
                    brand.ProductCount = 0;
            });
            _(filter.filterOptionsAvailable.filter_categorySubCategories).each(function(category){
                if (filter.filterOptionsApplied.filter_categorySubCategories[category.ID])
                    category.ProductCount = filter.filterOptionsApplied.filter_categorySubCategories[category.ID].ProductCount;
                else
                    category.ProductCount = 0;
            });

            // console.log(filter);
            // console.log(data.shop.info.count, productItems.length, this.length);

            // debugger;
            this.setExtras('filter', filter);
            this.setExtras('hasMoreProducts', data.shop.info.count > productItems.length + this.length);

            return productItems;
        }
    });

    return ListProductCatalog;
});