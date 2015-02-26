define([
    'backbone'
], function (Backbone) {

    var Stats = Backbone.Model.extend({
        type: false,
        url: function () {
            var _params = {
                source: 'shop',
                fn: 'shopstats'
            };
            if (this.type) {
                _params.type = this.type;
            }
            return APP.getApiLink(_params);
        }
    });

    return Stats;
});