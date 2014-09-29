define('plugin/shop/toolbox/js/collection/filterTreeCategories', [
    'cmn_jquery',
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'plugin/shop/toolbox/js/model/category',
    'default/js/lib/utils',
    'default/js/lib/cache'
    // 'default/js/lib/backbone-pageable',
], function ($, _, Backbone, ModelCategory, Utils, Cache) {

    var CategoriesTree = Backbone.Collection.extend({

        extras: {},

        queryParams: Cache.get('shopCategoriesTreeRD') || {},

        model: ModelCategory,

        url: function () {
            var urlOptions = {
                source: 'shop',
                fn: 'categories',
                limit: "0"
            };

            if (this.queryParams.removed) {
                urlOptions.removed = true;
            }

            Cache.set('shopCategoriesTreeRD', this.queryParams);

            return APP.getApiLink(urlOptions);
        },

        parse: function (data) {
            var map = {},
                tree = {};
            // create map
            _(data.items).each(function (item) {
                map[item.ID] = item;
                map[item.ID].childItems = {};
                map[item.ID].childCount = 0;
            });
            // link each item to it's own parent node
            _(map).each(function (item) {
                if (map[item.ParentID]) {
                    map[item.ParentID].childItems[item.ID] = item;
                    map[item.ParentID].childCount++;
                }
            });
            // collect roots
            _(map).each(function (item) {
                if (item.ParentID === null) {
                    tree[item.ID] = item;
                }
            })
            this.extras.tree = tree;
            this.extras.withRemoved = this.queryParams.removed;
            return data.items;
        },

        fetchWithRemoved: function (includeRemoved, fetchOptions) {
            this.queryParams.removed = includeRemoved;
            this.fetch(fetchOptions);
        }

    });

    return CategoriesTree;

});