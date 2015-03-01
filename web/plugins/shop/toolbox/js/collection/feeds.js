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
        url: APP.getApiLink({
            source: 'shop',
            fn: 'feeds'
        }),
        comparator: function (model) {
            return -model.get('time');
        },
        generateNewProductFeed: function () {
            var that = this,
                jobUrl = APP.getApiLink({
                    source: 'shop',
                    fn: 'feeds',
                    generate: true
                });
            Backbone.$.post(jobUrl, function () {
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