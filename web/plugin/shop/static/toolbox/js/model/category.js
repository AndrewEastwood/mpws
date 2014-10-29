define('plugin/shop/toolbox/js/model/category', [
    'default/js/lib/backbone'
], function (Backbone) {

    var Category = Backbone.Model.extend({
        idAttribute: "ID",
        url: function () {
            var _params =  {
                source: 'shop',
                fn: 'category'
            };
            if (!this.isNew()) {
                _params.id = this.id;
            }
            return APP.getApiLink(_params);
        }
    });

    return Category;

});