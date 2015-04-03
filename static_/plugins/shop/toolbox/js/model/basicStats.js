define([
    'backbone'
], function (Backbone) {

    var Stats = Backbone.Model.extend({
        type: false,
        url: function () {
            var _params = {};
            if (this.type) {
                _params.type = this.type;
            }
            return APP.getApiLink('shop', 'shopstats', _params);
        }
    });

    return Stats;
});