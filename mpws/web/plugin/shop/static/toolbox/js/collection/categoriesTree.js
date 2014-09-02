define('plugin/shop/toolbox/js/collection/categoriesTree', [
    'cmn_jquery',
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'plugin/shop/toolbox/js/model/category'
    // 'default/js/lib/backbone-pageable',
], function ($, _, Backbone, ModelCategory) {

    var CategoriesTree = Backbone.Collection.extend({

        model: ModelCategory,

        url: APP.getApiLink({
            source: 'shop',
            fn: 'categories',
            type: 'all'
        }),

        parse: function (data) {
            return data.items;
            // return Utils.getTreeByJson(data && data.categories, 'ID', 'ParentID');
        }

    });

    return CategoriesTree;

});