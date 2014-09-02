define('plugin/shop/toolbox/js/collection/categoriesTree', [
    'default/js/lib/sandbox',
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'default/js/lib/utils',
    'plugin/shop/toolbox/js/model/category'
    // 'default/js/lib/backbone-pageable',
], function (Sandbox, _, Backbone, Utils, ModelCategory) {

    var CategoriesTree = Backbone.Collection.extend({

        model: ModelCategory,

        url: APP.getApiLink({
            source: 'shop',
            fn: 'shop_manage_categories',
            action: 'list'
        }),

        parse: function (data) {
            return Utils.getTreeByJson(data && data.categories, 'ID', 'ParentID');
        }

    });

    return CategoriesTree;

});