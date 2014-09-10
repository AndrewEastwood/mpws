define('plugin/shop/toolbox/js/model/origin', [
    'default/js/lib/backbone',
    'default/js/lib/cache'
], function (Backbone, Cache) {

    var Origin = Backbone.Model.extend({
        idAttribute: "ID",
        url: function () {
            var _params =  {
                source: 'shop',
                fn: 'origin'
            };
            if (!this.isNew())
                _params.id = this.id;
            return APP.getApiLink(_params);
        },
        initialize: function () {
            var _statuses = Cache.getCookie('shop:statuses:list');
            if (_statuses && _statuses['shop_origins']) {
                this.extras = {
                    statuses: _statuses['shop_origins']
                }
            }
        }
    });

    return Origin;
});