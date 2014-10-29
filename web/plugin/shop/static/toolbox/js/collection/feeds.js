define('plugin/shop/toolbox/js/collection/feeds', [
    'cmn_jquery',
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'plugin/shop/toolbox/js/model/feed',
    'default/js/lib/utils',
    'default/js/lib/cache'
], function ($, _, Backbone, ModelFeed, Utils, Cache) {

    var Feeds = Backbone.Collection.extend({

        model: ModelFeed,

        url: APP.getApiLink({
            source: 'shop',
            fn: 'feeds'
        }),

        parse: function (data) {
            return data.feeds;
        }

    });

    return Feeds;

});