define('plugin/shop/site/js/model/productItemBase', [
    'default/js/lib/backbone'
], function (Backbone) {

    // debugger;
    // var ProductItemBase = MModel.getNew();
    return Backbone.Model.extend({
        idAttribute: "ID"
    });

});