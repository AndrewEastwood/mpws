define('plugin/shop/toolbox/js/model/basicStats', [
    'default/js/lib/backbone'
], function (Backbone) {

    var Stats = Backbone.Model.extend({
        type: false,
        url: function () {
            var _params = {
                source: 'shop',
                fn: 'stats'
            };
            if (this.type) {
                _params.type = this.type;
            }
            return APP.getApiLink(_params);
        }
    });

    return Stats;
});