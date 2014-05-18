define('plugin/shop/toolbox/js/model/origin', [
    'default/js/lib/sandbox',
    'default/js/model/mModel',
    'plugin/shop/common/js/lib/utils'
], function (Sandbox, MModel, ShopUtils) {

    var Model = MModel.getNew();
    var Origin = Model.extend({
        source: 'shop',
        fn: 'shop_manage_origins',
        // urlOptions: {
        //     action: 'get'
        // },
        parse: function (data) {
            // debugger;
            var _data = this.extractModelDataFromResponse(data);
            return _data;
        },
        list: function () {
            debugger;
            this.fetch({
                action: 'list'
            });
        },
        create: function (data) {
            debugger;
            $.post(this.getUrl({
                action: 'create'
            }), data).done(function(){
                Sandbox.eventNotify('plugin:shop:origin:created');
            });
        },
        update: function (originID, data) {
            // debugger;
            $.post(this.getUrl({
                action: 'update',
                originID: originID
            }), data).done(function(){
                Sandbox.eventNotify('plugin:shop:origin:updated');
            });
        },
        getItem: function (options, fetchOptions) {
            // debugger;
            var originID = fetchOptions.viewOptions && fetchOptions.viewOptions.originID;
            this.fetch({
                action: 'get',
                originID: originID
            }, fetchOptions);
        },
        statuses: function (options, fetchOptions) {
            this.fetch({
                action: 'statuses'
            }, fetchOptions);
        },
    });

    return Origin;

});