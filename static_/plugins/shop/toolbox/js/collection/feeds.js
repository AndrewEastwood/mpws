define([
    'jquery',
    'underscore',
    'backbone',
    'plugins/shop/toolbox/js/model/feed',
    'utils',
    'cachejs'
], function ($, _, Backbone, ModelFeed, Utils, Cache) {

    var Feeds = Backbone.Collection.extend({
        model: ModelFeed,
        url: APP.getApiLink('shop', 'feeds'),
        comparator: function (model) {
            return -model.get('time');
        },
        generateNewProductFeed: function () {
            var that = this,
                jobUrl = APP.getApiLink('shop','feeds',{
                    generate: true
                });
            Backbone.$.get(jobUrl, function () {
                that.fetch({reset: true});
            });
        },
        parse: function (data) {
            // debugger;
            return data;
        }
    });

    return Feeds;

});