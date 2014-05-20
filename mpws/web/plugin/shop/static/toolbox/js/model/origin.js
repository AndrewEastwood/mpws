define('plugin/shop/toolbox/js/model/origin', [
    'default/js/lib/sandbox',
    'default/js/model/mModel',
    'plugin/shop/common/js/lib/utils'
], function (Sandbox, MModel, ShopUtils) {

    var Model = MModel.getNew();
    var Origin = Model.extend({
        source: 'shop',
        fn: 'shop_manage_origins',
        initialize: function () {
            MModel.prototype.initialize.call(this);
        },
        // list: function () {
        //     debugger;
        //     this.fetch({
        //         action: 'list'
        //     });
        // },
        getList: ShopUtils.getOriginList,
        createItem: ShopUtils.createOrigin,
        updateItem: ShopUtils.updateOrigin,
        getItem: ShopUtils.getOrigin,
        getStatusList: ShopUtils.getOriginStatusList,
        // getItem: function (options, fetchOptions) {
        //     // debugger;
        //     var originID = fetchOptions.viewOptions && fetchOptions.viewOptions.originID;
        //     this.fetch({
        //         action: 'get',
        //         originID: originID
        //     }, fetchOptions);
        // },
        // statuses: function (options, fetchOptions) {
        //     this.fetch({
        //         action: 'statuses'
        //     }, fetchOptions);
        // },
    });

    return Origin;

});