define([
    'jquery',
    'underscore',
    'backbone',
    'plugins/shop/toolbox/js/model/category',
    'utils',
    'cachejs'
], function ($, _, Backbone, ModelCategory, Utils, Cache) {

    var CategoriesTree = Backbone.Collection.extend({

        model: ModelCategory,

        url: function () {
            var urlOptions = {
                limit: null,
                removed: !!this.queryParams.removed
            };

            return APP.getApiLink('shop','categories',urlOptions);
        },

        initialize: function () {
            this.extras = {};
            this.queryParams = Cache.get('shopCategoriesTreeRD') || {};
        },

        fetch: function (options) {
            Cache.set('shopCategoriesTreeRD', this.queryParams);
            return Backbone.Collection.prototype.fetch.call(this, options);
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