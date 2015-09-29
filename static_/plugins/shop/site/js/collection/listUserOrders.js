define([
    'underscore',
    'backbone'
], function (_, Backbone) {

    var ListUserOrders = Backbone.Collection.extend({
        url: function () {
            var options = _.extend({
                limit: 16
            }, _(this.getOptions() || {}).omit('design'));
            return APP.getApiLink('shop', 'orders', 'activeuser', options);
        },
        setOptions: function (options) {
            this.options = options;
        },
        getOptions: function () {
            return this.options;
        },
        parse: function (data) {
            return data.items;
        }
    });

    return ListUserOrders;
});