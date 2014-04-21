define('plugin/shop/toolbox/js/model/dashboard', [
    'default/js/model/mModel',
    'plugin/shop/common/js/lib/utils'
], function (MModel, Utils) {

    var Model = MModel.getNew();
    var ProductItemFull = Model.extend({
        source: 'shop',
        fn: 'toolbox_dashboard',
        parse: function (response) {
            return this.extractModelDataFromResponse(response);
        }
    });

    return ProductItemFull;

});