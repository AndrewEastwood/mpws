qB.Modules.register("collection/BCellCollectionClass", [], [

    'lib/jquery',
    'lib/underscore',
    'lib/backbone',
    'model/BCellModelClass'

], function(qB, Sandbox, $, _, Backbone, BCellModelClass){

    var BCellCollectionClass = Backbone.Collection.extend({
        model : BCellModelClass,
        comparator : function (cell) {
            return cell.get('displayIndex');
        }
    });

    return BCellCollectionClass;

})