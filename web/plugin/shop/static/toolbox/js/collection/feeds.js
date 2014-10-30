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
        }
    });

    return Feeds;

});