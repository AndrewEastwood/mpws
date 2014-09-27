define('plugin/shop/toolbox/js/collection/filterPanelOrigins', [
    'cmn_jquery',
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'plugin/shop/toolbox/js/model/origin',
    'default/js/lib/cache'
], function ($, _, Backbone, ModelOrigin, Cache) {

    var OriginsFilter = Backbone.Collection.extend({

        extras: {},

        queryParams: Cache.get('shopOriginsFilterRD') || {},

        model: ModelOrigin,

        url: function () {
            var urlOptions = {
                source: 'shop',
                fn: 'origins',
                type: 'list'
            };

            if (this.queryParams.removed) {
                urlOptions.removed = true;
            }

            Cache.set('shopOriginsFilterRD', this.queryParams);

            return APP.getApiLink(urlOptions);
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