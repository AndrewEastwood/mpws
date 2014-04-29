define("plugin/account/common/js/model/accountCreate", [
    'default/js/model/mModel',
    'default/js/lib/utils'
], function (MModel, Utils) {

    var Model = MModel.getNew();

    var AccountCreate = Model.extend({
        source: 'account',
        fn: 'create',
    });

    return AccountCreate;

});