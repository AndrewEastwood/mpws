define('plugin/shop/toolbox/js/collection/filterTreeCategories', [
    'cmn_jquery',
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'plugin/shop/toolbox/js/collection/listCategories',
    'default/js/lib/utils',
    'default/js/lib/cache'
<<<<<<< Updated upstream:web/plugin/shop/static/toolbox/js/collection/filterTreeCategories.js
], function ($, _, Backbone, ModelCategory, Utils, Cache) {
=======
    // 'default/js/lib/backbone-pageable',
], function ($, _, Backbone, CollectionListCategories, Utils, Cache) {
>>>>>>> Stashed changes:mpws/web/plugin/shop/static/toolbox/js/collection/filterTreeCategories.js

    var CategoriesTree = CollectionListCategories.extend({

<<<<<<< Updated upstream:web/plugin/shop/static/toolbox/js/collection/filterTreeCategories.js
        model: ModelCategory,

        url: function () {
            var urlOptions = {
                source: 'shop',
                fn: 'categories',
                limit: null,
                removed: !!this.queryParams.removed
            };

            return APP.getApiLink(urlOptions);
        },

        initialize: function () {
            this.extras = {};
            this.queryParams = Cache.get('shopCategoriesTreeRD') || {};
        },

        fetch: function (options) {
            Cache.set('shopCategoriesTreeRD', this.queryParams);
            return Backbone.Collection.prototype.fetch.call(this, options);
=======
        extras: {},

        initialize: function () {
            CollectionListCategories.prototype.initialize.call(this);
            this.state.pageSize = void(0);
>>>>>>> Stashed changes:mpws/web/plugin/shop/static/toolbox/js/collection/filterTreeCategories.js
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
<<<<<<< Updated upstream:web/plugin/shop/static/toolbox/js/collection/filterTreeCategories.js
            this.extras.withRemoved = this.queryParams.removed;
=======
            this.extras.withRemoved = this.state.removed;
>>>>>>> Stashed changes:mpws/web/plugin/shop/static/toolbox/js/collection/filterTreeCategories.js
            return data.items;
        },

        fetchWithRemoved: function (includeRemoved, fetchOptions) {
<<<<<<< Updated upstream:web/plugin/shop/static/toolbox/js/collection/filterTreeCategories.js
            this.queryParams.removed = includeRemoved;
=======
            this.state.removed = includeRemoved;
            // this.setCustomQueryField('Status', );
>>>>>>> Stashed changes:mpws/web/plugin/shop/static/toolbox/js/collection/filterTreeCategories.js
            this.fetch(fetchOptions);
        }

    });

    return CategoriesTree;

});