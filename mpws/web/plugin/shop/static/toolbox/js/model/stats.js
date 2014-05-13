define('plugin/shop/toolbox/js/model/stats', [
    'default/js/model/mModel',
    'plugin/shop/common/js/lib/utils'
], function (MModel, Utils) {

    var Model = MModel.getNew();
    var ProductItemFull = Model.extend({
        source: 'shop',
        fn: 'shop_manage_stats',
        parse: function (response) {
            return this.extractModelDataFromResponse(response);
        }
    });

    return ProductItemFull;

});