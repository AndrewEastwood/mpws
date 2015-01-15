define('plugin/system/toolbox/js/collection/customers', [
    'cmn_jquery',
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'plugin/system/toolbox/js/model/customer',
    'default/js/lib/utils',
    'default/js/lib/cache'
], function ($, _, Backbone, ModelFeed, Utils, Cache) {

    var Feeds = Backbone.Collection.extend({
        model: ModelFeed,
        url: APP.getApiLink({
            source: 'system',
            fn: 'customers'
        }),
        // comparator: function (model) {
        //     return -model.get('time');
        // },
        // generateNewProductFeed: function () {
        //     var that = this,
        //         jobUrl = APP.getApiLink({
        //             source: 'shop',
        //             fn: 'feeds',
        //             generate: true
        //         });
        //     Backbone.$.post(jobUrl, function () {
        //         that.fetch({reset: true});
        //     });
        // },
        parse: function (data) {
            // debugger;
            return data;
        }
    });

    return Feeds;

});