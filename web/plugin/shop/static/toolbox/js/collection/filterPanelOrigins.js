define('plugin/shop/toolbox/js/collection/filterPanelOrigins', [
    'cmn_jquery',
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'plugin/shop/toolbox/js/model/origin',
    'default/js/lib/cache'
], function ($, _, Backbone, ModelOrigin, Cache) {

    var OriginsFilter = Backbone.Collection.extend({

        model: ModelOrigin,

        url: function () {
            var urlOptions = {
                source: 'shop',
                fn: 'origins',
                type: 'list',
                removed: !!this.queryParams.removed
            };

            return APP.getApiLink(urlOptions);
        },

        initialize: function () {
            this.extras = {};
            this.queryParams = Cache.get('shopOriginsFilterRD') || {};
        },

        fetch: function (options) {
            Cache.set('shopOriginsFilterRD', this.queryParams);
            return Backbone.Collection.prototype.fetch.call(this, options);
        },

        parse: function (data) {
            this.extras.withRemoved = this.queryParams.removed;
            return data.items;
        },

        fetchWithRemoved: function (includeRemoved, fetchOptions) {
            this.queryParams.removed = includeRemoved;
            this.fetch(fetchOptions);
        }

    });

    return OriginsFilter;

});