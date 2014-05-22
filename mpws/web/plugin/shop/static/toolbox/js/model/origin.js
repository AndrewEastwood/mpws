define('plugin/shop/toolbox/js/model/origin', [
    'default/js/lib/backbone'
], function (Backbone) {

    var Origin = Backbone.Model.extend({
        idAttribute: "ID",
        url: APP.getApiLink({
            source: 'shop',
            fn: 'shop_manage_origin'
        }),
        parse: function (resp) {
            var origin = resp.origin || {};
            if (resp.statuses)
                origin.statuses = resp.statuses;
            return origin;
        }
    });

    return Origin;

});